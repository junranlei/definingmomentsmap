<?php

use yii\db\Migration;

class m200623_045755_009_create_table_map extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%map}}', [
            'id' => $this->primaryKey(10),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'timeCreated' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'timeUpdated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'publicPermission' => $this->integer(1)->notNull()->defaultValue('1'),
            'status' => $this->integer(1)->notNull()->defaultValue('1'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%map}}');
    }
}
