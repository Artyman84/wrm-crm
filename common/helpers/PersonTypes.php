<?php


namespace common\helpers;


use common\models\PersonType;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

/**
 * Class PersonTypes
 * @package common\helpers
 */
class PersonTypes
{
    public const TYPE_USER = 'user';
    public const TYPE_PARTNER = 'partner';
    public const TYPE_STUDENT = 'student';

    /**
     * @var string[]
     */
    private $types;

    /**
     * @param PersonType[]|array $types
     */
    public function getInstance(array $types): PersonTypes
    {
        $personTypes = [];
        foreach ($types as $type) {
            $personTypes[$type] = $type instanceof PersonType
                ? $type->type
                : $type;
        }

        if (count($personTypes) != count(array_intersect_key($personTypes, self::getTypes()))) {
            throw new InvalidArgumentException(sprintf('%s: Invalid values for the person types', Json::encode($personTypes)));
        }

        return new self($personTypes);
    }

    /**
     * PersonTypes constructor.
     * @param array $types
     */
    protected function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_USER => Yii::t('app', 'System User'),
            self::TYPE_PARTNER => Yii::t('app', 'Partner'),
            self::TYPE_STUDENT => Yii::t('app', 'Student'),
        ];
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return isset($this->types[self::TYPE_USER]);
    }

    /**
     * @return bool
     */
    public function isPartner(): bool
    {
        return isset($this->types[self::TYPE_PARTNER]);
    }

    /**
     * @return bool
     */
    public function isStudent(): bool
    {
        return isset($this->types[self::TYPE_STUDENT]);
    }
}