<?php

use yii\db\Migration;

class m250222_052212_026_create_table_historicalMapLink extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%historicalMapLink}}', [
            'histId' => $this->integer(10)->notNull(),
            'mapId' => $this->integer(10)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARYKEY', '{{%historicalMapLink}}', ['histId', 'mapId']);
        $this->createIndex('mapId', '{{%historicalMapLink}}', 'mapId');
        $this->addForeignKey('historicalmaplink_ibfk_1', '{{%historicalMapLink}}', 'histId', '{{%historicalFact}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('historicalmaplink_ibfk_2', '{{%historicalMapLink}}', 'mapId', '{{%map}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%historicalMapLink}}');
    }
}
