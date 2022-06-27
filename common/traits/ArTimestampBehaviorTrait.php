<?php


namespace common\traits;


use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Class ArTimestampBehaviorTrait
 * @package common\traits
 */
trait ArTimestampBehaviorTrait
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(
            [
                [
                    'class' => TimestampBehavior::class,
                    'value' => new Expression('NOW()'),
                ],
            ],
            parent::behaviors(),
        );
    }

}