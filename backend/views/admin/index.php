<?php

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?= \hail812\adminlte\widgets\Alert::widget(
                [
                    'type' => 'success',
                    'body' => '<h3>Congratulations!</h3>',
                ]
            ) ?>
        </div>
    </div>
</div>