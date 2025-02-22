<?php

use yii\db\Migration;

class m250222_052212_010_create_table_layer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%layer}}', [
            'id' => $this->primaryKey(10),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'type' => $this->integer(2)->notNull()->defaultValue('1'),
            'nameOrUrl' => $this->string(),
            'visible' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'mapId' => $this->integer(10)->notNull(),
            'date' => $this->date()->notNull(),
            'externalId' => $this->string(),
            'status' => $this->integer(1)->notNull()->defaultValue('1'),
        ], $tableOptions);

        $this->createIndex('mapId', '{{%layer}}', 'mapId');
    }

    public function down()
    {
        $this->dropTable('{{%layer}}');
    }
}
