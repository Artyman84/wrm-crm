<?php


namespace common\services;


use backend\models\dto\PersonDTO;
use backend\models\forms\PersonForm;
use backend\models\forms\PersonImageForm;
use common\models\Person;
use common\repositories\BaseRepository;
use common\repositories\PersonRepository;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

/**
 * Class PersonService
 * @package common\services
 */
class PersonService extends BaseService
{
    /**
     * @var PersonRepository|string
     */
    protected string|BaseRepository $repositoryClassName = PersonRepository::class;

    /**
     * @param Person $person
     * @return Person
     */
    public function save(Person $person): Person
    {
        $isNewRecord = $person->isNewRecord;
        try {
            if (!$person->save()) {
                throw new InvalidArgumentException(
                    sprintf("Failed to create person. Errors:\n%s", Json::encode($person->errors))
                );
            }
        } catch (Exception $e) {
            if ($isNewRecord && isset($person->photo)) {
                PersonImageForm::deleteImage(PersonForm::UPLOAD_PATH, $person->photo);
            }
            throw new InvalidArgumentException($e->getMessage());
        }

        return $person;
    }
}