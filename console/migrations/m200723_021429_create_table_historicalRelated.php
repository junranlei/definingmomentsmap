<?php

use yii\db\Migration;

class m200723_021429_create_table_historicalRelated extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%historicalRelated}}', [
            'histId1' => $this->integer(10)->notNull(),
            'histId2' => $this->integer(10)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARYKEY', '{{%historicalRelated}}', ['histId1', 'histId2']);
        $this->createIndex('histId2', '{{%historicalRelated}}', 'histId2');
        $this->addForeignKey('historicalrelated_ibfk_1', '{{%historicalRelated}}', 'histId1', '{{%historicalFact}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('historicalrelated_ibfk_2', '{{%historicalRelated}}', 'histId2', '{{%historicalFact}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%historicalRelated}}');
    }
}
