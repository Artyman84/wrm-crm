<?php

use backend\models\search\users\UserSearch;
use common\helpers\UserRole;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $iso2Countries array */
/* @var $role string */
/* @var $canCreate bool */

$this->title = Yii::t('app', UserRole::getInstance($role)->getText() . 's');
$this->params['breadcrumbs'][] = $this->title;
$this->params['modal'][] = [
    'id' => 'user-modal',
    'size' => Modal::SIZE_EXTRA_LARGE,
    'title' => Yii::t('app', 'User Info'),
    'footer' => '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>',
];

?>

<div class="user-index">

    <?php if ($canCreate): ?>
        <p class="text-right">
            <?= Html::a(Yii::t('app', 'Add ' . UserRole::getInstance($role)->getText()), ['user/create/' . $role], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => $searchModel->getColumns()
    ]); ?>
<?php
// Обновление картинки флага после pjax grid view update
if ($iso2Countries):
    $phoneSearchId = Html::getInputId($searchModel, 'phone');
    $js = <<<JS
        $("#$phoneSearchId").on("countrychange", function() {
          $("input[name='country-flag']").val($("#$phoneSearchId").intlTelInput("getSelectedCountryData").iso2);
        });
        if($("input[name='country-flag']").val()) {
            $("#$phoneSearchId").intlTelInput("setCountry", $("input[name='country-flag']").val());
        } else {
            $("#$phoneSearchId").intlTelInput("setCountry", "$iso2Countries[0]");
        }
JS;

    $this->registerJs($js);
endif;
?>

    <?php Pjax::end(); ?>
    <?= Html::hiddenInput('country-flag', '');?>
</div>
