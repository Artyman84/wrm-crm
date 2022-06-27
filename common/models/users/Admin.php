<?php


namespace common\models\users;


use common\helpers\UserRBAC;


/**
 * Class Admin
 * @package common\models\users
 */
final class Admin extends BaseUser
{
    /**
     * @return string
     */
    static function getCreatePermName(): string
    {
        return UserRBAC::PERM_CAN_CREATE_ADMIN;
    }

    /**
     * @return string
     */
    static function getUpdatePermName(): string
    {
        return UserRBAC::PERM_CAN_UPDATE_ADMIN;
    }

    /**
     * @return string
     */
    static function getRole(): string
    {
        return UserRBAC::ROLE_ADMIN;
    }
}