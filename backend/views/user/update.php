<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $userInfo common\models\User */
/* @var $uploadImage app\models\forms\UploadImage */
/* @var $availableCountries []
 */

$this->title = Yii::t('app', 'Update User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">
    <?= $this->render('_form', compact('userInfo', 'uploadImage', 'availableCountries')) ?>
</div>
