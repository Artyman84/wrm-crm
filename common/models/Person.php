<?php

namespace common\models;

use backend\models\dto\PersonDTO;
use backend\models\forms\PersonImageForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\dto\ActiveRecordTransformDTOInterface;
use common\models\dto\BaseDTOInterface;
use common\models\query\CountryQuery;
use common\models\query\PersonQuery;
use common\models\query\PersonTypeQuery;
use common\models\query\UserQuery;
use common\traits\ArTimestampBehaviorTrait;
use DateTime;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%person}}".
 *
 * @property int $id
 * @property int $country_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $photo
 * @property string|null $birth_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Country $country
 * @property User $user
 * @property PersonType $personTypes
 * @property PersonImageForm $personImageForm
 */
class Person extends ActiveRecord implements ActiveRecordTransformDTOInterface
{
    use ArTimestampBehaviorTrait;

    /**
     * @var PersonImageForm|null
     */
    public ?PersonImageForm $personImageForm = null;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->personImageForm = new PersonImageForm();
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%person}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country_id', 'first_name', 'last_name', 'phone'], 'required'],
            [['country_id'], 'integer'],
            [['birth_date', 'created_at', 'updated_at'], 'safe'],
            [['birth_date'], 'date', 'format' => 'dd-mm-yyyy'],
            [['photo', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['first_name', 'last_name', 'patronymic'], 'string', 'max' => 50],
//            [['phone'], PhoneInputValidator::class, 'region' => Yii::$app->user->identity->getAvailableCountriesISO2(false)],
            [['phone'], 'string', 'max' => 30],
            [['phone'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],

            [['personImageForm'], 'validateImage'],
        ];
    }

    /**
     * @param string $attribute
     * @param array|null $params
     */
    public function validateImage(string $attribute,  $params) {
        if (!$this->$attribute->validate()) {
            $this->addErrors([$attribute, $this->$attribute->getErrors()]);
        }
    }

    /**
     * @throws Exception
     */
    public function afterFind()
    {
        parent::afterFind();
        if ($this->birth_date) {
            $this->birth_date = (new DateTime($this->birth_date))->format('d-m-Y');
        }
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        $this->birth_date = (new DateTime($this->birth_date))->format('Y-m-d');
        if ($this->personImageForm) {
            $oldPhoto = $this->getOldAttribute('photo');
            $newPhoto = $this->uploadImage();

            if ($newPhoto) {
                $this->photo = $newPhoto;
            }

            if ($oldPhoto !== null && ($this->photo != $oldPhoto) ) {
                PersonImageForm::deleteImage(PersonImageForm::UPLOAD_PATH, $oldPhoto);
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return string|null
     * @throws \yii\base\Exception
     */
    private function uploadImage(): ?string
    {
        $personImageForm = $this->personImageForm;
        $personImageForm->imageFile = UploadedFile::getInstance($personImageForm, 'imageFile');
        if (!is_null($personImageForm->imageFile) && $personImageForm->imageFile->size !== 0) {
            return $personImageForm->upload(PersonImageForm::UPLOAD_PATH, 650, 800);
        }

        return null;
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery|CountryQuery
     */
    public function getCountry(): ActiveQuery|CountryQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return UserQuery|null
     */
    public function getUser(): ?ActiveQuery
    {
        return $this->hasOne(User::class, ['person_id' => 'id']);
    }

    /**
     * Gets query for [[PersonType]].
     *
     * @return PersonTypeQuery
     */
    public function getPersonTypes(): ActiveQuery
    {
        return $this->hasMany(PersonType::class, ['person_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return PersonQuery the active query used by this AR class.
     */
    public static function find(): PersonQuery
    {
        return new PersonQuery(static::class);
    }

    /**
     * @param bool $patronymic
     * @return string
     */
    public function getFullName(bool $patronymic = true): string
    {
        $fullName = $this->last_name . ' ' . $this->first_name;
        if ($patronymic && $this->patronymic) {
            $fullName .= ' ' . $this->patronymic;
        }

        return $fullName;
    }

    /**
     * @param bool $thumb
     * @return string|null
     */
    public function getPhotoUrl(bool $thumb = false): ?string
    {
        if ($this->photo) {
            $photo = ($thumb ? 'thumb_' : '') . $this->photo;
            return Url::to(sprintf('/uploads/%s/%s', PersonImageForm::UPLOAD_PATH, $photo), true);
        }

        return null;
    }

    /**
     * @param PersonDTO $personDTO
     * @return $this
     */
    public function loadFromDTO(BaseDTOInterface $personDTO): static
    {
        $this->first_name = $personDTO->firstName;
        $this->last_name = $personDTO->lastName;
        $this->patronymic = $personDTO->patronymic;
        $this->email = $personDTO->email;
        $this->phone = $personDTO->phone;
        $this->country_id = $personDTO->countryId;
        $this->birth_date = $personDTO->birthDate;
        $this->photo = $personDTO->photo;

        return $this;
    }
}
