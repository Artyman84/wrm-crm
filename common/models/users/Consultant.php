<?php


namespace common\models\users;


use common\helpers\UserRBAC;


/**
 * Class Consultant
 * @package common\models\users
 */
final class Consultant extends BaseUser
{
    /**
     * @return string
     */
    static function getCreatePermName(): string
    {
        return UserRBAC::PERM_CAN_CREATE_CONSULTANT;
    }

    /**
     * @return string
     */
    static function getUpdatePermName(): string
    {
        return UserRBAC::PERM_CAN_UPDATE_CONSULTANT;
    }

    /**
     * @return string
     */
    static function getRole(): string
    {
        return UserRBAC::ROLE_CONSULTANT;
    }
}