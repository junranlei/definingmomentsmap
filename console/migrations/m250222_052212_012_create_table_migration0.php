<?php

use yii\db\Migration;

class m250222_052212_012_create_table_migration0 extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%migration0}}', [
            'version' => $this->string(180)->notNull()->append('PRIMARY KEY'),
            'apply_time' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%migration0}}');
    }
}
