<?php

namespace common\models;

use common\models\query\PersonQuery;
use common\models\query\PersonTypeQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%person_type}}".
 *
 * @property int $id
 * @property string $type
 * @property int $person_id
 *
 * @property Person $person
 */
class PersonType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%person_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'person_id'], 'required'],
            [['person_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['type', 'person_id'], 'unique', 'targetAttribute' => ['type', 'person_id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'person_id' => Yii::t('app', 'Person ID'),
        ];
    }

    /**
     * Gets query for [[Person]].
     *
     * @return PersonQuery
     */
    public function getPerson(): ActiveQuery
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }

    /**
     * {@inheritdoc}
     * @return PersonTypeQuery the active query used by this AR class.
     */
    public static function find(): PersonTypeQuery
    {
        return new PersonTypeQuery(get_called_class());
    }
}
