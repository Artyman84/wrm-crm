<?php


namespace common\services;

use common\repositories\BaseRepositoryInterface;

/**
 * Interface BaseServiceInterface
 * @package common\services
 */
interface BaseServiceInterface
{
    /**
     * @return BaseRepositoryInterface
     */
    public function getRepository(): BaseRepositoryInterface;
}