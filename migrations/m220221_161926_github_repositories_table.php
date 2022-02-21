<?php

use yii\db\Migration;

/**
 * Class m220221_161926_github_users_repos
 */
class m220221_161926_github_repositories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%github_repositories}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(1000)->notNull(),
            'full_name' => $this->string(1000)->notNull(),
            'github_user_id' => $this->integer()->notNull(),
            'updated' => $this->dateTime()->notNull(),
            
        ]);
        $this->addCommentOnColumn('{{%github_repositories}}', 'updated', 'Дата обновления репозитория');
        $this->addForeignKey('fk_user_id', '{{%github_repositories}}', 'github_user_id', '{{%github_users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220221_161926_github_users_repos cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220221_161926_github_users_repos cannot be reverted.\n";

        return false;
    }
    */
}
