<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Person */
/* @var $form yii\widgets\ActiveForm */
/* @var $availableCountries common\models\Country[] */
/* @var $availableCountriesISO2 array */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-address-book"></i> <?= Yii::t('app', 'Account Data')?></h3>
                    </div>
                    <div class="card-body">

                        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'country_id')->widget(\app\widgets\CountrySelect2::class, [
                            'options' => ['placeholder' => Yii::t('app', 'Select a country ...')],
                            'countries' => $availableCountries,
                        ]) ?>


                        <?= $form->field($model, 'phone')->widget(\borales\extensions\phoneInput\PhoneInput::class, [
                            'name' => 'phone_number',
                            'jsOptions' => [
                                'allowExtensions' => true,
                                'preferredCountries' => $availableCountriesISO2,
                            ]
                        ]) ?>

                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'birth_date')->widget(\kartik\date\DatePicker::class, [
                            'language' => 'ru',
                            'options' => ['placeholder' => Yii::t('app', 'Enter birth date ...')],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]
                        ]) ?>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-image"></i> <?= Yii::t('app', 'Photo')?></h3>
                    </div>
                    <div class="card-body">
                        <?php $fileInputConfig = [
                            'options' => [
                                'accept' => 'image/jpg',
                                'multiple' => false,
                                'previewSettings' => ["image" => ["width" => "auto", "height" => "50px"]],
                            ],
                            'language' => 'ru',
                            'pluginOptions' => [
                                'allowedFileExtensions'=>['jpg', 'png'],
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'showCancel' => false,
                                'browseClass' => 'btn btn-success',
                                'browseLabel' => Yii::t('app', 'Выбрать'),
                                'removeClass' => 'btn btn-danger',
                                'removeLabel' => Yii::t('app', 'Удалить'),
                                'initialPreview' => [$model->getPhotoUrl()],
                                'initialPreviewAsData' => true,
                                'initialCaption' => $model->photo,
                                'initialPreviewConfig' => [
                                    [
                                        'caption' => Yii::t('app', 'User-Photo'),
                                    ],
                                ],
                                'overwriteInitial' => true,
                            ],
                            'pluginEvents' => [
                                'fileclear' => "function() { $('#" . Html::getInputId($model, 'photo') . "').val('') }",
                            ]
                        ];
                        ?>
                        <?= $form->field($model, 'photo')->hiddenInput()->label(false); ?>
                        <?= $form->field($model->personImageForm, 'imageFile')->widget(\kartik\file\FileInput::class, $fileInputConfig);?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card-footer">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
