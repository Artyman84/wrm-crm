<?php

namespace backend\controllers;

use backend\models\forms\PersonForm;
use common\helpers\UserRBAC;
use common\services\PersonService;
use Yii;
use common\models\Person;
use backend\models\search\PersonSearch;
use backend\controllers\BackendController;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends BackendController
{
    /**
     * @var PersonService
     */
    private PersonService $service;

    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [UserRBAC::ROLE_CONSULTANT],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * PersonController constructor.
     * @param $id
     * @param $module
     * @param PersonService $service
     * @param array $config
     */
    public function __construct($id, $module, PersonService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Person();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model = $this->service->save($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $availableCountries = $this->currentUser->availableCountries;
        $availableCountriesISO2 = $this->currentUser->availableCountriesISO2;

        return $this->render('create', [
            'model' => $model,
            'availableCountries' => $availableCountries,
            'availableCountriesISO2' => $availableCountriesISO2,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->service->getRepository()->find($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model = $this->service->save($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $availableCountries = $this->currentUser->availableCountries;
        $availableCountriesISO2 = $this->currentUser->availableCountriesISO2;

        return $this->render('update', [
            'model' => $model,
            'availableCountries' => $availableCountries,
            'availableCountriesISO2' => $availableCountriesISO2,
        ]);
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
