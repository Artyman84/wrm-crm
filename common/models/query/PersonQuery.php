<?php

namespace common\models\query;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the ActiveQuery class for [[\common\models\Person]].
 *
 * @see \common\models\User
 */
class PersonQuery extends ActiveQuery
{
    use QueryTrait;
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function byCountries(array $countries): PersonQuery
    {
        return $this->where([$this->aliased('country_id') => $countries]);
    }

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|ActiveRecord|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
