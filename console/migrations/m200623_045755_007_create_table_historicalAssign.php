<?php

use yii\db\Migration;

class m200623_045755_007_create_table_historicalAssign extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%historicalAssign}}', [
            'histId' => $this->integer(10)->notNull(),
            'userId' => $this->integer(10)->notNull(),
            'assignedTime' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedTime' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'type' => $this->integer(2)->notNull()->defaultValue('1'),
            'notes' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARYKEY', '{{%historicalAssign}}', ['histId', 'userId']);
        $this->createIndex('userId', '{{%historicalAssign}}', 'userId');
    }

    public function down()
    {
        $this->dropTable('{{%historicalAssign}}');
    }
}
