<?php


namespace backend\assets;


use yii\web\AssetBundle;

/**
 * Class FlagAsset
 * @package backend\assets
 */
class FlagAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/flag-icon-css';

    public $css = [
        'css/flag-icons.css'
    ];
}