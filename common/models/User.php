<?php
namespace common\models;

use common\helpers\UserRBAC;
use common\helpers\UserStatus;
use common\models\query\UserQuery;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\traits\ArTimestampBehaviorTrait;

/**
 * User model
 *
 * @property int $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $username
 * @property int $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $person_id
 * @property string $password write-only password
 * @property Person $person
 * @property Country[] $availableCountries
 * @property-read array $availableCountriesISO2
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ArTimestampBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'username', 'person_id'], 'required'],
            [['status', 'person_id'], 'integer'],
            [['status'], 'default', 'value' => UserStatus::STATUS_ACTIVE],
            [['status'], 'in', 'range' => array_keys(UserStatus::getStatuses())],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['password_reset_token', 'username'], 'unique'],
            [['person_id'], 'unique'],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'person_id' => Yii::t('app', 'Person'),
        ];
    }

    /**
     * Gets query for Person.
     * @return ActiveQuery
     */
    public function getPerson(): ActiveQuery
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getAvailableCountries(): ?ActiveQuery
    {
        if (Yii::$app->authManager->checkAccess($this->id, UserRBAC::ROLE_ADMIN)) {
            $activeQuery = (new ActiveQuery(Country::class, ['multiple' => true]));
        } else {
            $activeQuery = $this->hasMany(Country::class, ['id' => 'country_id'])
                ->viaTable(UserToCountry::tableName(), ['user_id' => 'id']);
        }

        return $activeQuery
            ->where(['is_disabled' => 0])
            ->orderBy('name')
            ->indexBy('id');
    }

    /**
     * @param bool $lowercase
     * @return array
     */
    public function getAvailableCountriesISO2(bool $lowercase = true): array
    {
        $availableCountries = $this->availableCountries;

        return array_values(
            array_map(function (Country $country) use($lowercase) {
                return $lowercase ? strtolower($country->code) : strtoupper($country->code);
            }, $availableCountries)
        );

//        $sqlFunction = $lowercase ? 'LOWER' : 'UPPER';
//        return $this
//            ->getAvailableCountries()
//            ->select("$sqlFunction(code)")
//            ->column();
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?User
    {
        return static::find()
            ->innerJoinWith(['person' => function(ActiveQuery $query) use ($email) {
                $query->andOnCondition(['email' => $email]);
            }])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::find()->where(['username' => $username])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => UserStatus::STATUS_DISABLED
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     * @param string $authKey
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
