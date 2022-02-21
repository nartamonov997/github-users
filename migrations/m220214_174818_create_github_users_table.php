<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%github_users}}`.
 */
class m220214_174818_create_github_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%github_users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%github_users}}');
    }
}
