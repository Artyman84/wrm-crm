<?php
/**
 * @var View $this
 */

use yii\bootstrap4\Modal;
use yii\web\View;

foreach ($this->params['modal'] as $modal):
    $modal['size'] = $modal['size'] ?? Modal::SIZE_LARGE;
    $content = $modal['content'] ?? '';
    unset($modal['content']);

    Modal::begin($modal);
        echo $content;
    Modal::end();
endforeach;