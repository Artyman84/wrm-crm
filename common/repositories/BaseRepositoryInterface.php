<?php


namespace common\repositories;


use yii\db\ActiveRecord;

/**
 * Interface BaseRepositoryInterface
 * @package common\repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): ActiveRecord;
}