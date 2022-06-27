<?php


namespace backend\models\dto;


use backend\models\forms\PersonForm;
use common\models\dto\BaseDTOInterface;
use JetBrains\PhpStorm\Pure;
use yii\base\Model;

/**
 * Class PersonDTO
 * @package backend\models\dto
 */
class PersonDTO implements BaseDTOInterface
{
    /**
     * @var string|null
     */
    public ?string $firstName;
    /**
     * @var string|null
     */
    public ?string $lastName;
    /**
     * @var string|null
     */
    public ?string $patronymic;
    /**
     * @var string|null
     */
    public ?string $email;
    /**
     * @var string|null
     */
    public ?string $phone;
    /**
     * @var int|null
     */
    public ?int $countryId;
    /**
     * @var string|null
     */
    public ?string $birthDate;
    /**
     * @var string|null
     */
    public ?string $photo;


    /**
     * @param PersonForm $model
     * @return PersonDTO
     */
    #[Pure] public static function transformFromPersonForm(Model $model): static
    {
        $dto = new PersonDTO();
        $dto->firstName = $model->first_name;
        $dto->lastName = $model->last_name;
        $dto->patronymic = $model->patronymic;
        $dto->email = $model->email;
        $dto->phone = $model->phone;
        $dto->countryId = $model->country_id;
        $dto->birthDate = $model->birth_date;
        $dto->photo = $model->photo;

        return $dto;
    }
}