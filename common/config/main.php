<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'formatter' => [
            'locale' => 'ru',
            'defaultTimeZone' => 'Europe/Kiev',
            'dateFormat' => 'dd-MM-yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'USD',
            'nullDisplay' => 'N/A',
        ],
        'telegram' => [
            'class' => 'SonkoDmitry\Yii\TelegramBot\Component',
            'apiToken' => 'YOUR_BOT_API_TOKEN',
        ],
    ],
];
