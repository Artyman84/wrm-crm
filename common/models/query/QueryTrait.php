<?php


namespace common\models\query;

/**
 * Trait QueryTrait
 * @package common\models\query
 */
trait QueryTrait
{
    /**
     * Returns aliased field
     * @param string $field
     * @return string
     */
    public function aliased(string $field): string
    {
        $table = $this->getTableNameAndAlias();
        return $table[1] . '.' . $field;
    }
}