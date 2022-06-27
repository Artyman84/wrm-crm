<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $person common\models\Person */
/* @var $uploadImage backend\models\forms\PersonImageForm */
/* @var $availableCountries [] */
/* @var $role string  */


use common\helpers\UserRole;

$this->title = Yii::t('app', sprintf('Create %s', UserRole::getInstance($role)->getText()));
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', sprintf('%ss', UserRole::getInstance($role)->getText())), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?= $this->render('_form', compact('user', 'person', 'uploadImage', 'availableCountries', 'role')) ?>
</div>
