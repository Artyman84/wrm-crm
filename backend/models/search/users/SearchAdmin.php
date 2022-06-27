<?php


namespace backend\models\search\users;


use app\widgets\CountrySelect2;
use borales\extensions\phoneInput\PhoneInput;
use common\helpers\UserRBAC;
use common\helpers\UserStatus;
use common\models\users\Admin;
use DateTime;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use Yii;
use yii\base\Exception;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\debug\models\search\UserSearchInterface;
use yii\helpers\Url;

/**
 * Class SearchAdmin
 * @package backend\models\search\users
 */
class SearchAdmin extends UserSearch implements UserSearchInterface
{
    /**
     * @var int
     */
    public $country_id;

    /**
     * @var string
     */
    public $full_username;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'full_username', 'status', 'role', 'phone', 'email', 'country_id', 'created_at'], 'safe'],
        ];
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return Admin::class;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return UserRBAC::ROLE_ADMIN;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return DataProviderInterface
     * @throws Exception
     */
    public function search($params): DataProviderInterface
    {
        $query = $this->getSearchQuery();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['user.id' => $this->id])
            ->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['user.status' => $this->status])
            ->andFilterWhere(['like', 'CONCAT(person.last_name, " ", person.first_name, " ", person.patronymic)', $this->full_username])
            ->andFilterWhere(['person.country_id' => $this->country_id])
            ->andFilterWhere(['like', 'person.phone', $this->phone])
            ->andFilterWhere(['DATE_FORMAT(user.created_at, "%Y-%m-%d")' => $this->created_at ? (new DateTime($this->created_at))->format('Y-m-d') : null]);

        $dataProvider->sort->attributes['full_username'] = [
            'asc' => ['CONCAT(person.last_name, person.first_name, person.patronymic)' => SORT_ASC],
            'desc' => ['CONCAT(person.last_name, person.first_name, person.patronymic)' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['country_id'] = [
            'asc' => ['person.country_id' => SORT_ASC],
            'desc' => ['person.country_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['email'] = [
            'asc' => ['person.email' => SORT_ASC],
            'desc' => ['person.email' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phone'] = [
            'asc' => ['person.phone' => SORT_ASC],
            'desc' => ['person.phone' => SORT_DESC],
        ];

        return $dataProvider;
    }

    /**
     * @return array[]
     * @throws \Exception
     */
    public function getColumns(): array
    {
        return [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
            ],

            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width: 100px;'],
                'contentOptions' => ['class' => 'align-middle text-center'],
            ],

            [
                'attribute' => 'photo',
                'enableSorting' => false,
                'label' => Yii::t('app', 'Photo'),
                'contentOptions' => ['class' => 'align-middle text-center img-thumb-small-block'],
                'format' => 'raw',
                'value' => function(Admin $model) {
                    if (isset($model->person->photo)) {
                        return Html::img($model->person->getPhotoUrl(true), ['alt ' => Yii::t('app', 'Photo'), 'class' => 'img img-thumbnail rounded']);
                    }
                    return null;
                }
            ],

            [
                'attribute' => 'full_username',
                'label' => Yii::t('app', 'User name'),
                'contentOptions' => ['class' => 'align-middle'],
                'format' => 'raw',
                'value' => function(Admin $model) {
                    return Html::a($model->person->getFullName(), '#', [
                        'data-toggle' => 'modal',
                        'data-target' => '#user-modal',
                        'data-modal-url' => Url::to(['user/view-content', 'role' => $this->getRole(), 'id' => $model->id]),
                        'class' => 'js-modal-load text-nowrap',
                    ]);
                }
            ],

            [
                'attribute' => 'country_id',
                'label' => Yii::t('app', 'Country'),
                'format' => 'html',
                'contentOptions' => ['class' => 'align-middle text-center'],
                'filter' => CountrySelect2::widget([
                                                       'model' => $this,
                                                       'attribute' => 'country_id',
                                                       'options' => ['placeholder' => Yii::t('app', 'Select a country ...')],
                                                       'pluginOptions' => [
                                                           'allowClear' => true
                                                       ],
                                                       'countries' => $this->webUser->identity->availableCountries,
                                                   ]),
                'value' => function(Admin $model) {
                    $code = strtolower($model->person->country->code);
                    return "<img class='country-flag' src='/img/flags/4x3/$code.svg' alt=''> {$model->person->country->name}";
                }
            ],

            [
                'attribute' => 'email',
                'format' => 'email',
                'value' => function(Admin $model) {
                    return $model->person->email;
                },
                'contentOptions' => ['class' => 'align-middle text-center'],
            ],

            [
                'attribute' => 'phone',
                'filter' => PhoneInput::widget([
                                                   'model' => $this,
                                                   'attribute' => 'phone',
                                                   'jsOptions' => [
                                                       'allowExtensions' => true,
                                                       'onlyCountries' => $this->webUser->identity->availableCountriesISO2
                                                   ]
                                               ]),
                'value' => function(Admin $model) {
                    return $model->person->phone;
                },
                'contentOptions' => ['class' => 'align-middle text-center'],
            ],

            [
                'attribute' => 'created_at',
                'label' => Yii::t('app', 'In system since'),
                'format' => 'datetime',
                'contentOptions' => ['class' => 'align-middle text-center'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'attribute' => 'created_at',
                    'language' => 'ru',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'locale' => [
                            'format' => 'dd-mm-yyyy'
                        ],
                    ],
                ],
            ],

            [
                'attribute' => 'status',
                'format' => 'html',
                'filter' => UserStatus::getStatuses(),
                'value' => function(Admin $model) {
                    return UserStatus::getInstance($model->status)->getIcon();
                },
                'contentOptions' => ['class' => 'align-middle text-center'],
            ],

            [
                'class' => '\kartik\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'template' => '{update}',
                'buttons' => [
                    'update' => function(string $url, Admin $model) {
                        if ($this->canUpdate($model)) {
                            return Html::a('<i class="fas fa-user-edit"></i>', Url::to(['user/update', 'role' => $this->getRole(), 'id' => $model->id]), ['title' => Yii::t('app', 'Edit')]);
                        }
                        return false;
                    },
                ],
            ],
        ];
    }
}