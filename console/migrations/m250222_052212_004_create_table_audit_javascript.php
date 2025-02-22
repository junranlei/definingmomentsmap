<?php

use yii\db\Migration;

class m250222_052212_004_create_table_audit_javascript extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_javascript}}', [
            'id' => $this->primaryKey(),
            'entry_id' => $this->integer()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'type' => $this->string(20)->notNull(),
            'message' => $this->text()->notNull(),
            'origin' => $this->string(512),
            'data' => $this->binary(),
        ], $tableOptions);

        $this->addForeignKey('fk_audit_javascript_entry_id', '{{%audit_javascript}}', 'entry_id', '{{%audit_entry}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%audit_javascript}}');
    }
}
