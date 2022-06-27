<?php


namespace backend\components\rbac\rules;


use common\models\User;
use yii\rbac\Rule;

/**
 * Class CanCreateUserRule
 * @package components\rbac\rules
 */
class IsMyselfRule extends Rule
{
    public $name = 'isMyself';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        /** @var User $userModel */
        $userModel = $params['user'] ?? null;
        return $userModel && $userModel->id == $user;
    }
}