<?php
/* @var $this yii\web\View */

use common\helpers\UserRBAC;
use common\models\User;
use yii\helpers\Url;

/**
 * @var User $user
 */
$user = Yii::$app->user->identity;
$authManager = Yii::$app->authManager;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::to('/')?>" class="brand-link">
        <img src="/img/wrm-logo.png" alt="WRM - ERP Logo" class="brand-image img-circle elevation-3" >
        <span class="brand-text font-weight-light"><?= Yii::t('app', Yii::$app->name)?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $user->person->getPhotoUrl(true)?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?= Url::to(['user/update', 'id' => $user->person->id])?>" class="d-block"><?= $user->person->getFullName(false) ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    [
                        'label' => 'Starter Pages',
                        'visible' => false,
                        'icon' => 'tachometer-alt',
                        'badge' => '<span class="right badge badge-info">2</span>',
                        'items' => [
                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                        ]
                    ],
                    ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>', 'visible' => false],

                    ['label' => 'Yii2 PROVIDED', 'header' => true, 'visible' => YII_DEBUG],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank', 'visible' => YII_DEBUG],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank', 'visible' => YII_DEBUG],
                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true, 'visible' => false],
                    [
                        'label' => 'Level1',
                        'visible' => false,
                        'items' => [
                            ['label' => 'Level2', 'iconStyle' => 'far'],
                            [
                                'label' => 'Level2',
                                'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                                ]
                            ],
                            ['label' => 'Level2', 'iconStyle' => 'far']
                        ]
                    ],

                    ['label' => Yii::t('app', 'MANAGEMENT'), 'header' => true],

                    [
                        'label' => Yii::t('app', 'Persons'),
                        'visible' => Yii::$app->user->can(UserRBAC::ROLE_MANAGER),
                        'iconClass' => 'nav-icon fas fa-address-book',
                        'url' => ['person/index'],
                        'active' => $this->context->id == 'person',
                    ],

                    [
                        'label' => Yii::t('app', 'Users'),
                        'visible' => true,
                        'icon' => 'users',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Super Admins'),
                                'visible' => Yii::$app->user->can(UserRBAC::ROLE_SUPER_ADMIN),
                                'iconClass' => 'nav-icon fas fa-star text-red',
                                'url' => ['user/index', 'role' => UserRBAC::ROLE_SUPER_ADMIN],
                                'active' => $this->context->id == 'user' && Yii::$app->request->get('role') == UserRBAC::ROLE_SUPER_ADMIN,
                            ],
                            [
                                'label' => Yii::t('app', 'Admins'),
                                'visible' => Yii::$app->user->can(UserRBAC::ROLE_ADMIN),
                                'iconClass' => 'nav-icon fas fa-star text-orange',
                                'iconStyle' => 'far',
                                'url' => ['user/index', 'role' => UserRBAC::ROLE_ADMIN],
                                'active' => $this->context->id == 'user' && Yii::$app->request->get('role') == UserRBAC::ROLE_ADMIN,
                            ],
                            [
                                'label' => Yii::t('app', 'Managers'),
                                'visible' => Yii::$app->user->can(UserRBAC::ROLE_MANAGER),
                                'iconClass' => 'nav-icon fas fa-star text-green',
                                'iconStyle' => 'far',
                                'url' => ['user/index', 'role' => UserRBAC::ROLE_MANAGER],
                                'active' => $this->context->id == 'user' && Yii::$app->request->get('role') == UserRBAC::ROLE_MANAGER,
                            ],
                            [
                                'label' => Yii::t('app', 'Consultants'),
                                'visible' => Yii::$app->user->can(UserRBAC::ROLE_CONSULTANT),
                                'iconClass' => 'nav-icon fas fa-star text-blue',
                                'iconStyle' => 'far',
                                'url' => ['user/index', 'role' => UserRBAC::ROLE_CONSULTANT],
                                'active' => $this->context->id == 'user' && Yii::$app->request->get('role') == UserRBAC::ROLE_CONSULTANT,
                            ],
                        ]
                    ],

                    ['label' => 'LABELS', 'header' => true, 'visible' => false,],
                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger', 'visible' => false,],
                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning', 'visible' => false,],
                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info', 'visible' => false,],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>