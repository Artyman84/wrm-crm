<?php


namespace common\helpers;

use Yii;
use yii\base\InvalidArgumentException;

/**
 * Class UserStatus
 * @package common\helpers
 */
class UserStatus
{
    public const STATUS_DISABLED = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * @var int
     */
    private $status;

    /**
     * @param int $status
     * @return UserStatus
     */
    public static function getInstance(int $status): UserStatus
    {
        $statuses = self::getStatuses();
        if (!isset($statuses[$status])) {
            throw new InvalidArgumentException(sprintf('%s: Invalid value for the status', $status));
        }

        return new self($status);
    }

    /**
     * UserStatus constructor.
     * @param int $status
     */
    protected function __construct(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DISABLED => \Yii::t('app', 'Disabled'),
            self::STATUS_ACTIVE => \Yii::t('app', 'Active'),
        ];
    }

    /**
     * Get list of statuses icons.
     * @return array
     */
    public static function getIcons(): array
    {
        return [
            self::STATUS_ACTIVE => '<i class="fas fa-user text-success" title="' . Yii::t('app', 'Active') . '"></i>',
            self::STATUS_DISABLED => '<i class="fas fa-user text-danger" title="' . Yii::t('app', 'Disabled') . '"></i>',
        ];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status];
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        $statuses = self::getIcons();
        return $statuses[$this->status];
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->status == self::STATUS_DISABLED;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }
}