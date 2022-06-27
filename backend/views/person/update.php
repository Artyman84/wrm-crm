<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Person */
/* @var $availableCountries common\models\Country[] */
/* @var $availableCountriesISO2 array */

$this->title = Yii::t('app', 'Update Person: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-update">
    <?= $this->render('_form', compact('model', 'availableCountries', 'availableCountriesISO2')) ?>
</div><?php
