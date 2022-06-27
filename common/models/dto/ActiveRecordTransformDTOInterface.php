<?php


namespace common\models\dto;


use yii\db\ActiveRecord;

/**
 * Interface ActiveRecordDTOInterface
 * @package common\models\dto
 */
interface ActiveRecordTransformDTOInterface
{
    /**
     * @param BaseDTOInterface $personDTO
     * @return ActiveRecord
     */
    public function loadFromDTO(BaseDTOInterface $personDTO): ActiveRecord;
}