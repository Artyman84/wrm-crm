<?php

use backend\components\rbac\rules\IsMyselfRule;
use backend\components\rbac\rules\IsSystemRule;
use common\helpers\UserRBAC;
use yii\db\Migration;

/**
 * Class m211004_221231_create_superadmin
 */
class m211004_221231_create_superadmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // добавляем роль "consultant"/"консультант"
        $consultantRole = $auth->createRole('consultant');
        $auth->add($consultantRole);

        // добавляем роль "manager"/"менеджер"
        $managerRole = $auth->createRole('manager');
        $auth->add($managerRole);
        $auth->addChild($managerRole, $consultantRole);

        // добавляем роль "admin"/"админ"
        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);
        $auth->addChild($adminRole, $managerRole);

        // добавляем роль "superadmin"/"супер-админ"
        $superadminRole = $auth->createRole('superadmin');
        $auth->add($superadminRole);
        $auth->addChild($superadminRole, $adminRole);

        // Rules
        $isSystemRule = new IsSystemRule();
        $auth->add($isSystemRule);

        $isMyselfRule = new IsMyselfRule();
        $auth->add($isMyselfRule);


        // Permissions
        $canSuperAdminSelfUpdate = $auth->createPermission('canSuperAdminSelfUpdate');
        $canSuperAdminSelfUpdate->ruleName = $isMyselfRule->name;
        $auth->add($canSuperAdminSelfUpdate);

        $canAdminSelfUpdate = $auth->createPermission('canAdminSelfUpdate');
        $canAdminSelfUpdate->ruleName = $isMyselfRule->name;
        $auth->add($canAdminSelfUpdate);

        $canManagerSelfUpdate = $auth->createPermission('canManagerSelfUpdate');
        $canManagerSelfUpdate->ruleName = $isMyselfRule->name;
        $auth->add($canManagerSelfUpdate);

        $canConsultantSelfUpdate = $auth->createPermission('canConsultantSelfUpdate');
        $canConsultantSelfUpdate->ruleName = $isMyselfRule->name;
        $auth->add($canConsultantSelfUpdate);

        $canCreateSuperAdmin = $auth->createPermission('canCreateSuperAdmin');
        $canCreateSuperAdmin->ruleName = $isSystemRule->name;
        $auth->add($canCreateSuperAdmin);

        $canUpdateSuperAdmin = $auth->createPermission('canUpdateSuperAdmin');
        $auth->add($canUpdateSuperAdmin);
        $auth->addChild($canSuperAdminSelfUpdate, $canUpdateSuperAdmin);

        $canCreateAdmin = $auth->createPermission('canCreateAdmin');
        $auth->add($canCreateAdmin);
        $canUpdateAdmin = $auth->createPermission('canUpdateAdmin');
        $auth->add($canUpdateAdmin);
        $auth->addChild($canAdminSelfUpdate, $canUpdateAdmin);

        $canCreateManager = $auth->createPermission('canCreateManager');
        $auth->add($canCreateManager);
        $canUpdateManager = $auth->createPermission('canUpdateManager');
        $auth->add($canUpdateManager);
        $auth->addChild($canManagerSelfUpdate, $canUpdateManager);

        $canCreateConsultant = $auth->createPermission('canCreateConsultant');
        $auth->add($canCreateConsultant);
        $canUpdateConsultant = $auth->createPermission('canUpdateConsultant');
        $auth->add($canUpdateConsultant);
        $auth->addChild($canConsultantSelfUpdate, $canUpdateConsultant);


        // Roles
        $auth->addChild($superadminRole, $canSuperAdminSelfUpdate);
        $auth->addChild($superadminRole, $canCreateAdmin);
        $auth->addChild($superadminRole, $canUpdateAdmin);

        $auth->addChild($adminRole, $canAdminSelfUpdate);
        $auth->addChild($adminRole, $canCreateManager);
        $auth->addChild($adminRole, $canUpdateManager);

        $auth->addChild($managerRole, $canManagerSelfUpdate);
        $auth->addChild($managerRole, $canCreateConsultant);
        $auth->addChild($managerRole, $canUpdateConsultant);

        $auth->addChild($consultantRole, $canConsultantSelfUpdate);

        $this->insert('{{%person}}', [
            'country_id' => '124', // Moldova
            'first_name' => 'Артём',
            'last_name' => 'Яковец',
            'patronymic' => 'Владимирович',
            'email' => 'arty_itweb@mail.ru',
            'phone' => '+37311111111',
            'photo' => null,
            'birth_date' => '1984-10-17',
            'created_at' => (new DateTime())->format('Y-m-d'),
            'updated_at' => (new DateTime())->format('Y-m-d'),
        ]);
        $personId = $this->db->getLastInsertID();

        $this->insert('{{%user}}', [
            'auth_key' => '',
            'username' => 'arty_man',
            'password_hash' => '$2y$13$sRjkPYmoZr8CFTNlhAZ23u4OXq3s4nHSsbRUiker2ARzmCvskQMCW',
            'status' => '1',
            'created_at' => (new DateTime())->format('Y-m-d'),
            'updated_at' => (new DateTime())->format('Y-m-d'),
            'person_id' => $personId,
        ]);

        $userId = $this->db->getLastInsertID();
        $auth = Yii::$app->authManager;
        $role = $auth->getRole(UserRBAC::ROLE_SUPER_ADMIN);
        $auth->assign($role, $userId);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        $this->truncateTable('{{%person}}');
        $this->truncateTable('{{%user}}');

        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211004_221231_create_superadmin cannot be reverted.\n";

        return false;
    }
    */
}
