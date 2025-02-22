<?php

use yii\db\Migration;

class m250222_052212_018_create_table_flagNote extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%flagNote}}', [
            'id' => $this->primaryKey(10),
            'flagId' => $this->integer(10)->notNull(),
            'userId' => $this->integer(10)->notNull(),
            'note' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('flagNote_flagId', '{{%flagNote}}', 'flagId', '{{%flag}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('flagNote_userId', '{{%flagNote}}', 'userId', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%flagNote}}');
    }
}
