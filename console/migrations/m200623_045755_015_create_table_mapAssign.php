<?php

use yii\db\Migration;

class m200623_045755_015_create_table_mapAssign extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mapAssign}}', [
            'mapId' => $this->integer(10)->notNull(),
            'userId' => $this->integer(10)->notNull(),
            'assignedTime' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedTime' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'type' => $this->integer(2)->notNull()->defaultValue('1'),
            'qrCode' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARYKEY', '{{%mapAssign}}', ['mapId', 'userId']);
        $this->addForeignKey('mapassign_ibfk_1', '{{%mapAssign}}', 'mapId', '{{%map}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('mapassign_ibfk_2', '{{%mapAssign}}', 'userId', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%mapAssign}}');
    }
}
