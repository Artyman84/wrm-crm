<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person_type}}`.
 */
class m220531_204841_create_person_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person_type}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(50)->notNull(),
            'person_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'unique_idx-person_type-type_person_id',
            '{{%person_type}}',
            ['type', 'person_id'],
            true
        );

        $this->createIndex(
            'idx-person_type-person_id',
            '{{%person_type}}',
            'person_id'
        );

        $this->addForeignKey(
            'fk-person_type-person_id',
            '{{%person_type}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-person_type-person_id', '{{%person_type}}');
        $this->dropIndex('idx-person_type-person_id', '{{%person_type}}');
        $this->dropIndex('unique_idx-person_type-type_person_id', '{{%person_type}}');

        $this->dropTable('{{%person_type}}');
    }
}
