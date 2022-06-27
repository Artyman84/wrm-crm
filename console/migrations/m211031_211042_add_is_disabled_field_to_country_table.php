<?php

use yii\db\Migration;

/**
 * Class m211031_211042_add_is_disabled_field_to_country_table
 */
class m211031_211042_add_is_disabled_field_to_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%country%}}', 'is_disabled', $this->tinyInteger(1)->notNull()->defaultValue(0));
        $this->update('{{%country}}', ['is_disabled' => 1], ['NOT IN', 'code', ['RO', 'MD']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%country}}', 'is_disabled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211031_211042_add_is_disabled_field_to_country_table cannot be reverted.\n";

        return false;
    }
    */
}
