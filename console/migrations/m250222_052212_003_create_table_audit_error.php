<?php

use yii\db\Migration;

class m250222_052212_003_create_table_audit_error extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_error}}', [
            'id' => $this->primaryKey(),
            'entry_id' => $this->integer()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'message' => $this->text()->notNull(),
            'code' => $this->integer()->defaultValue('0'),
            'file' => $this->string(512),
            'line' => $this->integer(),
            'trace' => $this->binary(),
            'hash' => $this->string(32),
            'emailed' => $this->tinyInteger(1)->notNull()->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('idx_file', '{{%audit_error}}', 'file');
        $this->createIndex('idx_emailed', '{{%audit_error}}', 'emailed');
        $this->addForeignKey('fk_audit_error_entry_id', '{{%audit_error}}', 'entry_id', '{{%audit_entry}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%audit_error}}');
    }
}
