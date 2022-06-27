<?php


namespace common\models\users;

use common\helpers\UserRBAC;


/**
 * Class SuperAdmin
 * @package common\models
 */
final class SuperAdmin extends BaseUser
{
    /**
     * @return string
     */
    static function getCreatePermName(): string
    {
        return UserRBAC::PERM_CAN_CREATE_SUPER_ADMIN;
    }

    /**
     * @return string
     */
    static function getUpdatePermName(): string
    {
        return UserRBAC::PERM_CAN_UPDATE_SUPER_ADMIN;
    }

    /**
     * @return string
     */
    static function getRole(): string
    {
        return UserRBAC::ROLE_SUPER_ADMIN;
    }
}