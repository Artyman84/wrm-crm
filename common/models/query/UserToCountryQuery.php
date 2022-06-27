<?php

namespace common\models\query;

use common\models\UserToCountry;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\UserToCountry]].
 *
 * @see \common\models\UserToCountry
 */
class UserToCountryQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserToCountry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserToCountry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
