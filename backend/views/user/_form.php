<?php

use app\widgets\CountrySelect2;
use common\models\Country;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $person common\models\Person */
/* @var $uploadImage backend\models\forms\PersonImageForm */
/* @var $form ActiveForm */
/* @var $availableCountries [] */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-cog"></i> <?= Yii::t('app', 'Account Data')?></h3>
                    </div>
                        <div class="card-body">
                            <?= $form->field($user, 'username')->textInput() ?>

                            <?= $form->field($user, 'password_hash')->textInput() ?>

                            <?= $form->field($user, 'status')->widget(\kartik\select2\Select2::class, ['data' => \common\helpers\UserStatus::getStatuses()]) ?>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </div>
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-globe"></i> <?= Yii::t('app', 'Available Countries')?></h3>
                    </div>
                        <div class="card-body">
                            <?= $form->field($user, 'username')->textInput() ?>

                            <?= $form->field($user, 'password_hash')->textInput() ?>

                            <?= $form->field($user, 'status')->widget(\kartik\select2\Select2::class, ['data' => \common\helpers\UserStatus::getStatuses()]) ?>
                        </div>
                </div>
            </div>



            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-address-card"></i> <?= Yii::t('app', 'Profile Data')?></h3>
                    </div>

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
                            'initialPreview' => [$person->getPhotoUrl()],
                            'initialPreviewAsData' => true,
                            'initialCaption' => $person->photo,
                            'initialPreviewConfig' => [
                                [
                                    'caption' => Yii::t('app', 'User-Photo'),
                                ],
                            ],
                            'overwriteInitial' => true,
                        ],
                    ];
                    ?>

                        <div class="card-body">

                            <?= $form->field($person, 'last_name')->textInput() ?>

                            <?= $form->field($person, 'first_name')->textInput() ?>

                            <?= $form->field($person, 'patronymic')->textInput() ?>

                            <?= $form->field($person, 'email')->textInput(['type' => 'email']) ?>

                            <?= $form->field($person, 'phone')->widget(\borales\extensions\phoneInput\PhoneInput::class, [
                                'name' => 'phone_number',
                                'jsOptions' => [
                                    'allowExtensions' => true,
                                    'onlyCountries' => array_values(
                                        array_map(function (Country $country) {
                                            return strtolower($country->code);
                                        }, $availableCountries)
                                    )
                                ]
                            ]) ?>

                            <?= $form->field($person, 'birth_date')->widget(DatePicker::class, [
                                'language' => 'ru',
                                'options' => ['placeholder' => Yii::t('app', 'Enter birth date ...')],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy',
                                ]
                            ]) ?>

                            <?= $form->field($person, 'country_id')->widget(CountrySelect2::class, [
                                'countries' => Country::find()->orderBy('name ASC')->indexBy('id')->all(),
                                'options' => ['placeholder' => Yii::t('app', 'Select a country ...')],
                            ]) ?>

                            <?= $form->field($uploadImage, 'imageFile')->widget(FileInput::class, $fileInputConfig);?>

                            <?php if (!$person->isNewRecord) {
                                $deleteFieldId = Html::getInputId($uploadImage, 'delete');
                                $fileInputConfig['pluginEvents'] = [
                                    'fileclear' => "function() { $('#$deleteFieldId').val('1') }",
                                ];

                                echo $form->field($uploadImage, 'delete')->hiddenInput()->label(false);
                            }
                            ?>
                        </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
