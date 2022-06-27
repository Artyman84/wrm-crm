<?php

use common\models\Person;

/* @var $this yii\web\View */
/* @var $model Person */
/* @var $availableCountries common\models\Country[] */
/* @var $availableCountriesISO2 array */

$this->title = Yii::t('app', 'Create Person');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-create">
    <?= $this->render('_form', compact('model', 'availableCountries', 'availableCountriesISO2')) ?>
</div>
