<?php

namespace common\models\query;

use common\helpers\UserStatus;
use common\models\User;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\User]].
 *
 * @see \common\models\User
 */
class UserQuery extends ActiveQuery
{
    use QueryTrait;

    public function active(): UserQuery
    {
        return $this->andWhere([$this->aliased('status') => UserStatus::STATUS_ACTIVE]);
    }

    public function disabled(): UserQuery
    {
        return $this->andWhere([$this->aliased('status') => UserStatus::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
