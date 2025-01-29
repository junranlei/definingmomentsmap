<?php

use yii\db\Migration;

class m200623_045755_018_create_table_social_account extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%social_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'provider' => $this->string()->notNull(),
            'client_id' => $this->string()->notNull(),
            'code' => $this->string(32),
            'email' => $this->string(),
            'username' => $this->string(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx_social_account_provider_client_id', '{{%social_account}}', ['provider', 'client_id'], true);
        $this->createIndex('idx_social_account_code', '{{%social_account}}', 'code', true);
        $this->addForeignKey('fk_social_account_user', '{{%social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%social_account}}');
    }
}
