<?php


namespace common\repositories;


use common\exceptions\NotModelFoundException;
use common\exceptions\NotSetPropertyException;
use common\helpers\UserRBAC;
use common\models\User;
use common\models\users\Admin;
use common\models\users\BaseUser;
use common\models\users\Consultant;
use common\models\users\Manager;
use common\models\users\SuperAdmin;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * Class UserRepository
 * @package common\repositories
 *
 * @method SuperAdmin|Admin|Manager|Consultant find()
 */
class UserRepository extends BaseRepository
{
    /**
     *
     */
    private const ROLE_CLASS_NAMES = [
        UserRBAC::ROLE_SUPER_ADMIN => SuperAdmin::class,
        UserRBAC::ROLE_ADMIN => Admin::class,
        UserRBAC::ROLE_MANAGER => Manager::class,
        UserRBAC::ROLE_CONSULTANT => Consultant::class,
    ];

    /**
     * @var BaseUser
     */
    protected $className;

    /**
     * UserRepository constructor.
     * @param array $config
     * @throws InvalidArgumentException|NotSetPropertyException
     */
    public function __construct(array $config)
    {
        $role = $config['role'] ?? null;
        if (!isset(self::ROLE_CLASS_NAMES[$role])) {
            throw new InvalidArgumentException(sprintf('Error in UserRepository: %s - invalid value for the RBAC role', $role));
        }

        $this->className = self::ROLE_CLASS_NAMES[$role];

        parent::__construct();
    }

    /**
     * @return BaseUser
     */
    public function getClassName(): BaseUser
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getCreatePermName(): string
    {
        return $this->className::getCreatePermName();
    }

    /**
     * @return string
     */
    public function getUpdatePermName(): string
    {
        return $this->className::getUpdatePermName();
    }
}