<?php

use yii\db\Migration;

class m200623_045755_023_create_table_historicalMediaLink extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%historicalMediaLink}}', [
            'histId' => $this->integer(10)->notNull(),
            'mediaId' => $this->integer(10)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARYKEY', '{{%historicalMediaLink}}', ['histId', 'mediaId']);
        $this->createIndex('mediaId', '{{%historicalMediaLink}}', 'mediaId');
        $this->addForeignKey('historicalmedialink_ibfk_1', '{{%historicalMediaLink}}', 'histId', '{{%historicalFact}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('historicalmedialink_ibfk_2', '{{%historicalMediaLink}}', 'mediaId', '{{%media}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%historicalMediaLink}}');
    }
}
