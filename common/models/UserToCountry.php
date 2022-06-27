<?php

namespace common\models;

use common\models\query\CountryQuery;
use common\models\query\UserQuery;
use common\models\query\UserToCountryQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_to_country}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 *
 * @property Country $country
 * @property User $user
 */
class UserToCountry extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_to_country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'country_id'], 'required'],
            [['user_id', 'country_id'], 'integer'],
            [['user_id', 'country_id'], 'unique', 'targetAttribute' => ['user_id', 'country_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'country_id' => Yii::t('app', 'Country ID'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery|CountryQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserToCountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserToCountryQuery(static::class);
    }
}
