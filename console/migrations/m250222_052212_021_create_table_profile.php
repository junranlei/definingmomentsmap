<?php

use yii\db\Migration;

class m250222_052212_021_create_table_profile extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%profile}}', [
            'user_id' => $this->primaryKey(),
            'name' => $this->string(),
            'public_email' => $this->string(),
            'gravatar_email' => $this->string(),
            'gravatar_id' => $this->string(32),
            'location' => $this->string(),
            'website' => $this->string(),
            'timezone' => $this->string(40),
            'bio' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey('fk_profile_user', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
