<?php


namespace common\repositories;


use common\models\Person;

/**
 * Class PersonRepository
 * @package common\repositories
 */
class PersonRepository extends BaseRepository
{
    /**
     * @var string|Person
     */
    protected string|Person $className = Person::class;
}