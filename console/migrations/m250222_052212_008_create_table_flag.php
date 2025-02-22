<?php

use yii\db\Migration;

class m250222_052212_008_create_table_flag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%flag}}', [
            'id' => $this->primaryKey(10),
            'model' => $this->string(20)->notNull(),
            'modelId' => $this->integer(10)->notNull(),
            'times' => $this->integer(5)->notNull()->defaultValue('1'),
            'timeCreated' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'timeUpdated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%flag}}');
    }
}
