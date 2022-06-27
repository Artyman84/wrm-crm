<?php


namespace common\models\users;


use common\models\User;

/**
 * Class BaseUser
 * @package common\models\users
 */
abstract class BaseUser extends User
{
    /**
     * @return string
     */
    abstract static function getCreatePermName(): string;

    /**
     * @return string
     */
    abstract static function getUpdatePermName(): string;

    /**
     * @return string
     */
    abstract static function getRole(): string;
}