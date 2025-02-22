<?php

use yii\db\Migration;

class m250222_052212_006_create_table_audit_trail extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_trail}}', [
            'id' => $this->primaryKey(),
            'entry_id' => $this->integer(),
            'user_id' => $this->integer(),
            'action' => $this->string()->notNull(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->string()->notNull(),
            'field' => $this->string(),
            'old_value' => $this->text(),
            'new_value' => $this->text(),
            'created' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_audit_trail_action', '{{%audit_trail}}', 'action');
        $this->createIndex('idx_audit_user_id', '{{%audit_trail}}', 'user_id');
        $this->createIndex('idx_audit_trail_field', '{{%audit_trail}}', ['model', 'model_id', 'field']);
        $this->addForeignKey('fk_audit_trail_entry_id', '{{%audit_trail}}', 'entry_id', '{{%audit_entry}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%audit_trail}}');
    }
}
