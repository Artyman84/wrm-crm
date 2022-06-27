<?php


namespace backend\controllers;


use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class BackendController
 * @package backend\controllers
 */
abstract class BackendController extends Controller
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->currentUser = Yii::$app->user->identity;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}