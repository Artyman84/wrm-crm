<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>
<div class="user-view">

    <div class="row">
        <div class="col-md-6 text-left img-preview-block">
            <img src="<?= $model->person->getPhotoUrl()?>" class="img img-thumbnail rounded" alt="" style="">
        </div>

        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'last_name',
                    'first_name',
                    'patronymic',
                    'email:email',
                    'phone',
                    'birth_date:date',
                    [
                        'label' => 'Country',
                        'format' => 'raw',
                        'value' => function(\common\models\User $model) {
                            $code = strtolower($model->person->country->code);
                            return "<img class='country-flag' src='/img/flags/4x3/$code.svg' alt=''> {$model->person->country->name}";
                        },
//                        'contentOptions' => ['class' => 'bg-red'],     // настройка HTML атрибутов для тега, соответсвующего value
                    ],
                    [
                        'label' => Yii::t('app', 'Created on'),
                        'value' => $model->created_at,
                        'format' => 'date',
                    ]
                ],
            ]) ?>
        </div>

</div>
