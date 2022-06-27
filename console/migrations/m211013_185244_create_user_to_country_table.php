<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_to_country}}`.
 */
class m211013_185244_create_user_to_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_to_country}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'country_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-user_to_country-user_id_country_id',
            '{{%user_to_country}}',
            ['user_id', 'country_id'],
            true
        );

        $this->addForeignKey(
            'fk-user_to_country-user_id',
            '{{%user_to_country}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_to_country-country_id',
            '{{%user_to_country}}',
            'country_id',
            '{{%country}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_to_country-country_id', '{{%user_to_country}}');
        $this->dropForeignKey('fk-user_to_country-user_id', '{{%user_to_country}}');
        $this->dropIndex('idx-user_to_country-user_id_country_id', '{{%user_to_country}}');
        $this->dropTable('{{%user_to_country}}');
    }
}
