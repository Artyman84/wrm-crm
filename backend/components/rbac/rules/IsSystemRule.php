<?php


namespace backend\components\rbac\rules;


use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class IsSystemRule
 * @package components\rbac\rules
 */
class IsSystemRule extends Rule
{
    public $name = 'isSystem';

    /**
     * @param int|string $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        return Yii::$app instanceof \yii\console\Application;
    }
}