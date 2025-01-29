<?php

use yii\db\Migration;

class m200623_045755_024_create_table_feature extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%feature}}', [
            'id' => $this->primaryKey(10),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'geojson' => $this->text(),
            'visible' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'histId' => $this->integer(10)->notNull(),
            'status' => $this->integer()->notNull()->defaultValue('1'),
        ], $tableOptions);

        $this->createIndex('histId', '{{%feature}}', 'histId');
        $this->addForeignKey('feature_ibfk_1', '{{%feature}}', 'histId', '{{%historicalFact}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%feature}}');
    }
}
