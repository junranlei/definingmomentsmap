<?php

use yii\db\Migration;

class m250222_052212_014_create_table_userLog extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%userLog}}', [
            'id' => $this->integer(10)->notNull(),
            'pageAction' => $this->string()->notNull(),
            'accessTime' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'userId' => $this->integer(10)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%userLog}}');
    }
}
