<?php

use yii\db\Migration;

class m250222_052212_025_create_table_historicalFact extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%historicalFact}}', [
            'id' => $this->primaryKey(10),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'date' => $this->date()->notNull(),
            'dateEnded' => $this->date(),
            'timeCreated' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'urls' => $this->text(),
            'mainMediaId' => $this->integer(10),
            'right2Link' => $this->integer(1)->notNull()->defaultValue('1'),
            'publicPermission' => $this->integer(1)->notNull()->defaultValue('1'),
            'status' => $this->integer(1)->notNull()->defaultValue('1'),
            'timeUpdated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'flag' => $this->integer(1)->notNull()->defaultValue('0'),
            'source' => $this->string(),
        ], $tableOptions);

        $this->createIndex('title', '{{%historicalFact}}', 'title');
        $this->createIndex('mainMediaId', '{{%historicalFact}}', 'mainMediaId');
        $this->addForeignKey('historicalfact_ibfk_1', '{{%historicalFact}}', 'mainMediaId', '{{%media}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%historicalFact}}');
    }
}
