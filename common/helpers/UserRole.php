<?php


namespace common\helpers;

use Yii;
use yii\base\InvalidArgumentException;

/**
 * Class UserRole
 * @package common\models
 */
class UserRole
{
    private const ROLE_SUPER_ADMIN = 'superadmin';
    private const ROLE_ADMIN = 'admin';
    private const ROLE_MANAGER = 'manager';
    private const ROLE_CONSULTANT = 'consultant';

    /**
     * @var string
     */
    private $role;

    /**
     * @param string $role
     * @return UserRole
     */
    public static function getInstance(string $role): UserRole
    {
        $roles = self::getRoles();
        if (!isset($roles[$role])) {
            throw new InvalidArgumentException(sprintf('%s: Invalid value for the Role', $role));
        }

        return new self($role);
    }

    /**
     * UserStatus constructor.
     * @param string $role
     */
    protected function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * Get list of roles.
     * @return array
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => Yii::t('app', 'Super Admin'),
            self::ROLE_ADMIN => Yii::t('app', 'Admin'),
            self::ROLE_MANAGER => Yii::t('app', 'Manager'),
            self::ROLE_CONSULTANT => Yii::t('app', 'Consultant'),
        ];
    }

    /**
     * Get list of role icons.
     * @return array
     */
    public static function getRoleIcons(): array
    {
        $roles = self::getRoles();
        return [
            self::ROLE_SUPER_ADMIN => '<span class="text-red" title="' . $roles[self::ROLE_SUPER_ADMIN] . '"><i class="fas fa-star"></i></span>',
            self::ROLE_ADMIN => '<span class="text-orange" title="' . $roles[self::ROLE_ADMIN] . '"><i class="fas fa-star"></i></span>',
            self::ROLE_MANAGER => '<span class="text-green" title="' . $roles[self::ROLE_MANAGER] . '"><i class="fas fa-star"></i></span>',
            self::ROLE_CONSULTANT => '<span class="text-blue" title="' . $roles[self::ROLE_CONSULTANT] . '"><i class="fas fa-star"></i></span>',
        ];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $roles = self::getRoles();
        return $roles[$this->role];
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        $roles = self::getRoleIcons();
        return $roles[$this->role];
    }
}