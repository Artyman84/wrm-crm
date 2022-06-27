<?php

namespace common\models\query;

use common\models\Country;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Country]].
 *
 * @see \common\models\Country
 */
class CountryQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @return CountryQuery
     */
    public function notDisabled(): CountryQuery
    {
        return $this->andWhere(['is_disabled' => '0']);
    }

    /**
     * {@inheritdoc}
     * @return Country[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Country|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
