<?php

use yii\db\Migration;

class m250222_052212_023_create_table_token extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%token}}', [
            'user_id' => $this->integer(),
            'code' => $this->string(32)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_token_user_id_code_type', '{{%token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('fk_token_user', '{{%token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%token}}');
    }
}
