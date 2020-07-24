<?php

use yii\db\Migration;

class m200723_021419_create_table_apis extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%apis}}', [
            'id' => $this->primaryKey(5),
            'name' => $this->string(100)->notNull(),
            'description' => $this->string(),
            'url' => $this->string()->notNull(),
            'apikey' => $this->string(100),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%apis}}');
    }
}
