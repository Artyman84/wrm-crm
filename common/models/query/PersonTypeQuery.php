<?php

namespace common\models\query;

use common\models\PersonType;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[PersonType]].
 *
 * @see PersonType
 */
class PersonTypeQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PersonType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PersonType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
