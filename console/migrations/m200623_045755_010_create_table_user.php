<?php

use yii\db\Migration;

class m200623_045755_010_create_table_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'unconfirmed_email' => $this->string(),
            'registration_ip' => $this->string(45),
            'flags' => $this->integer()->notNull()->defaultValue('0'),
            'confirmed_at' => $this->integer(),
            'blocked_at' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'last_login_at' => $this->integer(),
            'last_login_ip' => $this->string(45),
            'auth_tf_key' => $this->string(16),
            'auth_tf_enabled' => $this->tinyInteger(1)->defaultValue('0'),
            'password_changed_at' => $this->integer(),
            'gdpr_consent' => $this->tinyInteger(1)->defaultValue('0'),
            'gdpr_consent_date' => $this->integer(),
            'gdpr_deleted' => $this->tinyInteger(1)->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('idx_user_email', '{{%user}}', 'email', true);
        $this->createIndex('idx_user_username', '{{%user}}', 'username', true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
