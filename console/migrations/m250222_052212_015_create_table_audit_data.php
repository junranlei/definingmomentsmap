<?php

use yii\db\Migration;

class m250222_052212_015_create_table_audit_data extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_data}}', [
            'id' => $this->primaryKey(),
            'entry_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'data' => $this->binary(),
            'created' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey('fk_audit_data_entry_id', '{{%audit_data}}', 'entry_id', '{{%audit_entry}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%audit_data}}');
    }
}
