<?php

use yii\db\Migration;

class m250222_052212_002_create_table_audit_entry extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_entry}}', [
            'id' => $this->primaryKey(),
            'created' => $this->dateTime()->notNull(),
            'user_id' => $this->integer()->defaultValue('0'),
            'duration' => $this->float(),
            'ip' => $this->string(45),
            'request_method' => $this->string(16),
            'ajax' => $this->integer(1)->notNull()->defaultValue('0'),
            'route' => $this->string(),
            'memory_max' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx_route', '{{%audit_entry}}', 'route');
    }

    public function down()
    {
        $this->dropTable('{{%audit_entry}}');
    }
}
