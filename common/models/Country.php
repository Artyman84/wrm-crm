<?php

namespace common\models;

use common\models\query\CountryQuery;
use common\models\query\EventCountryValueQuery;
use common\models\query\PersonQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $is_disabled
 *
 * @property Person[] $person
 */
class Country extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'code'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 2],
            [['is_disabled'], 'integer'],
            [['is_disabled'], 'in', 'range' => [0, 1]],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'is_disabled' => Yii::t('app', 'Is Disabled'),
        ];
    }

    /**
     * Gets query for [[Person]].
     *
     * @return ActiveQuery
     */
    public function getPerson(): ActiveQuery
    {
        return $this->hasMany(Person::class, ['country_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CountryQuery the active query used by this AR class.
     */
    public static function find(): CountryQuery
    {
        return new CountryQuery(static::class);
    }

    /**
     * @return array|Country[]
     */
    public static function getActiveCountryList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->orderBy('name ASC')
            ->all();
    }
}
