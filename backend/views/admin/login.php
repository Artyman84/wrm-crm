<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use common\models\LoginForm;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php
        $form = ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field(
            $model,
            'username',
            [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ]
        )
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field(
            $model,
            'password',
            [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ]
        )
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-8">
                <?= $form->field($model, 'rememberMe')->checkbox(
                    [
                        'template' => '<div class="icheck-primary">{input}{label}</div>',
                        'labelOptions' => [
                            'class' => ''
                        ],
                        'uncheck' => null
                    ]
                ) ?>
            </div>
            <div class="col-4">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>

        <?php
        ActiveForm::end(); ?>

        <!--        <div class="social-auth-links text-center mb-3">-->
        <!--            <p>- OR -</p>-->
        <!--            <a href="#" class="btn btn-block btn-primary">-->
        <!--                <i class="fab fa-facebook mr-2"></i> Sign in using Facebook-->
        <!--            </a>-->
        <!--            <a href="#" class="btn btn-block btn-danger">-->
        <!--                <i class="fab fa-google-plus mr-2"></i> Sign in using Google+-->
        <!--            </a>-->
        <!--        </div>-->
        <!-- /.social-auth-links -->

        <p class="mb-1 d-none">
            <a href="#">I forgot my password</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>