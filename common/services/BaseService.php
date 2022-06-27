<?php


namespace common\services;


use common\exceptions\NotSetPropertyException;
use common\repositories\BaseRepository;
use common\repositories\BaseRepositoryInterface;

/**
 * Class BaseService
 * @package common\services
 */
class BaseService implements BaseServiceInterface
{
    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    /**
     * @var BaseRepository|string
     */
    protected BaseRepository|string $repositoryClassName;

    /**
     * BaseService constructor.
     * @param array $config
     * @throws NotSetPropertyException
     */
    public function __construct(array $config = [])
    {
        if (is_null($this->repositoryClassName)) {
            throw new NotSetPropertyException('Property BaseService::repositoryClassName must be set. Null given.');
        }

        $this->repository = new $this->repositoryClassName($config);
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): BaseRepositoryInterface
    {
        return $this->repository;
    }
}