<?php


namespace common\helpers;


use Yii;

/**
 * Class UserRBAC
 * @package common\helpers
 */
class UserRBAC
{
    // Roles
    public const ROLE_SUPER_ADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_CONSULTANT = 'consultant';

    // Permissions
    public const PERM_CAN_CREATE_SUPER_ADMIN = 'canCreateSuperAdmin';
    public const PERM_CAN_UPDATE_SUPER_ADMIN = 'canUpdateSuperAdmin';

    public const PERM_CAN_CREATE_ADMIN = 'canCreateAdmin';
    public const PERM_CAN_UPDATE_ADMIN = 'canUpdateAdmin';

    public const PERM_CAN_CREATE_MANAGER = 'canCreateManager';
    public const PERM_CAN_UPDATE_MANAGER = 'canUpdateManager';

    public const PERM_CAN_CREATE_CONSULTANT = 'canCreateConsultant';
    public const PERM_CAN_UPDATE_CONSULTANT = 'canUpdateConsultant';



    /**
     * @var int
     */
    private $userId;

    /**
     * @var array
     */
    private static $permissionAccesses;

    /**
     * @var array
     */
    private static $roles;

    /**
     * @param int $userId
     * @return UserRBAC
     */
    public static function getInstance(int $userId): UserRBAC
    {
        return new self($userId);
    }

    /**
     * UserRBAC constructor.
     * @param int $userId
     */
    protected function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @deprecated
     * TODO: remove
     * @param string $permissionName
     * @return bool
     */
    public function checkAccess(string $permissionName): bool
    {
        if (!isset(self::$permissionAccesses[$this->userId][$permissionName])) {
            self::$permissionAccesses[$this->userId][$permissionName] = Yii::$app->authManager->checkAccess($this->userId, $permissionName);
        }

        return self::$permissionAccesses[$this->userId][$permissionName];
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * @return string[]
     */
    private function getRoles(): array
    {
        if (!isset(self::$roles[$this->userId])) {
            self::$roles[$this->userId] = array_keys(Yii::$app->authManager->getRolesByUser($this->userId));
        }

        return self::$roles[$this->userId];
    }

}