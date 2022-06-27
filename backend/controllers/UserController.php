<?php

namespace backend\controllers;

use backend\models\forms\PersonImageForm;
use backend\models\search\users\SearchAdmin;
use backend\models\search\users\SearchConsultant;
use backend\models\search\users\SearchManager;
use backend\models\search\users\SearchSuperAdmin;
use common\exceptions\NotModelFoundException;
use common\helpers\UserRBAC;
use common\helpers\UserRole;
use common\models\User;
use common\models\Person;
use common\services\UserService;
use yii\base\InvalidArgumentException;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackendController
{
    /**
     * @var UserService
     */
    private $service;

    /**
     * @var string
     */
    private $role;

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
                        'actions' => ['index', 'view', 'view-content'],
                        'matchCallback' => function (AccessRule $rule, $action) {
                            return Yii::$app->user->can($this->role);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function (AccessRule $rule, $action) {
                            $createPermName = $this->service->getRepository()->getCreatePermName();
                            return Yii::$app->user->can($createPermName);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function (AccessRule $rule, $action) {
                            $userId = Yii::$app->request->get('id');
                            $updatePermName = $this->service->getRepository()->getUpdatePermName();
                            return Yii::$app->user->can($updatePermName, ['user' => User::findOne($userId)]);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
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
     * UserController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        $role = Yii::$app->request->get('role');
        if (!$role) {
            throw new InvalidArgumentException('Role cannot be null.');
        }

        $this->role = $role;
        $this->service = new UserService(['role' => $role]);

        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all User models.
     * @return string
     * @throws \Exception
     */
    public function actionIndex(): string
    {
        $searchModel = $this->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $createPermName = $this->service->getRepository()->getCreatePermName();

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'iso2Countries' => array_values($this->currentUser->availableCountriesISO2),
                'role' =>
                    $this->role,
                'canCreate' => Yii::$app->user->can($createPermName),
            ]
        );
    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotModelFoundException
     */
    public function actionViewContent(): string
    {
        $id = Yii::$app->request->get('id');
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->service->getRepository()->find($id),
            ]);
        }

        throw new ForbiddenHttpException(Yii::t('app', 'Only ajax requests is available.'));
    }

    /**
     * Displays a single User model.
     * @return string
     * @throws NotModelFoundException if the model cannot be found
     */
    public function actionView(): string
    {
        $id = Yii::$app->request->get('id');
        return $this->render('view', [
            'model' => $this->service->getRepository()->find($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     */
    public function actionCreate()
    {
        $user = new User();
        $person = new Person();
        $uploadImage = new PersonImageForm();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($this->service->saveUserInfo($user, $uploadImage, $post)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'User was successfully created'));
                return $this->redirect(['user/index/' . $this->role]);
            }
        }

        $availableCountries = array_values($this->currentUser->availableCountriesISO2);
        $role = $this->role;
        return $this->render('create', compact('user', 'person', 'uploadImage', 'availableCountries', 'role'));
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotModelFoundException
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        /** @var User $userInfo */
        $userInfo = $this->service->getRepository()->find($id);
        $uploadImage = new PersonImageForm(['imgOldName' => $userInfo->photo]);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($this->service->saveUserInfo($userInfo, $uploadImage, $post)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'User was successfully updated'));
                return $this->redirect(['user/index']);
            }
        }

        $availableCountries = $this->currentUser->availableCountries;
        return $this->render('update', compact('userInfo', 'uploadImage', 'availableCountries'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        throw new ForbiddenHttpException(Yii::t('app', 'It is forbidden to delete users.'));
    }

    /**
     * @return SearchSuperAdmin|SearchAdmin|SearchManager|SearchConsultant
     */
    private function getSearchModel(): SearchSuperAdmin|SearchAdmin|SearchManager|SearchConsultant
    {
        $searchModels = [
            UserRBAC::ROLE_SUPER_ADMIN => SearchSuperAdmin::class,
            UserRBAC::ROLE_ADMIN => SearchAdmin::class,
            UserRBAC::ROLE_MANAGER => SearchManager::class,
            UserRBAC::ROLE_CONSULTANT => SearchConsultant::class,
        ];

        $searchClassName = $searchModels[$this->role] ?? null;
        if (is_null($searchClassName)) {
            throw new InvalidArgumentException(
                sprintf('Cannot find search model for user role = %s.', UserRole::getInstance($this->role)->getText())
            );
        }

        return new $searchClassName(Yii::$app->user);
    }
}
