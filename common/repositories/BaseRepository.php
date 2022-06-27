<?php


namespace common\repositories;


use common\exceptions\NotModelFoundException;
use common\exceptions\NotSetPropertyException;
use common\exceptions\SavingFailedModelException;
use Yii;
use yii\base\ExitException;
use yii\db\ActiveRecord;

/**
 * Class BaseRepository
 * @package common\repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var ActiveRecord
     */
    protected string|\common\models\Person $className;

    /**
     * BaseRepository constructor.
     * @throws NotSetPropertyException
     */
    public function __construct()
    {
        if (is_null($this->className)) {
            throw new NotSetPropertyException('Property BaseRepository::className must be set. Null given.');
        }
    }

    /**
     * @param int $id
     * @return ActiveRecord
     * @throws NotModelFoundException
     */
    public function find(int $id): ActiveRecord
    {
        if (($model = $this->className::findOne($id)) !== null) {
            return $model;
        }

        throw new NotModelFoundException("{$this->className} model was not found.");

    }
}