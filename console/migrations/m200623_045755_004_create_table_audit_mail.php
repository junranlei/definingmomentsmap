<?php

use yii\db\Migration;

class m200623_045755_004_create_table_audit_mail extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_mail}}', [
            'id' => $this->primaryKey(),
            'entry_id' => $this->integer()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'successful' => $this->integer()->notNull(),
            'from' => $this->string(),
            'to' => $this->string(),
            'reply' => $this->string(),
            'cc' => $this->string(),
            'bcc' => $this->string(),
            'subject' => $this->string(),
            'text' => $this->binary(),
            'html' => $this->binary(),
            'data' => $this->binary(),
        ], $tableOptions);

        $this->addForeignKey('fk_audit_mail_entry_id', '{{%audit_mail}}', 'entry_id', '{{%audit_entry}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%audit_mail}}');
    }
}
