<?php


namespace common\models\users;


use common\helpers\UserRBAC;

/**
 * Class Manager
 * @package common\models\users
 */
final class Manager extends BaseUser
{
    /**
     * @return string
     */
    static function getCreatePermName(): string
    {
        return UserRBAC::PERM_CAN_CREATE_MANAGER;
    }

    /**
     * @return string
     */
    static function getUpdatePermName(): string
    {
        return UserRBAC::PERM_CAN_UPDATE_MANAGER;
    }

    /**
     * @return string
     */
    static function getRole(): string
    {
        return UserRBAC::ROLE_MANAGER;
    }
}