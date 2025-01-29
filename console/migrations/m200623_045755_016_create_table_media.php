<?php

use yii\db\Migration;

class m200623_045755_016_create_table_media extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(10),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'type' => $this->integer(2)->notNull()->defaultValue('1'),
            'nameOrUrl' => $this->string(),
            'right2Link' => $this->integer(1)->notNull()->defaultValue('1'),
            'ownerId' => $this->integer(10)->notNull(),
            'isUrl' => $this->integer(1)->notNull()->defaultValue('0'),
            'creator' => $this->string(),
            'source' => $this->string(),
            'status' => $this->integer(1)->notNull()->defaultValue('1'),
            'publicPermission' => $this->integer(1)->notNull()->defaultValue('1'),
        ], $tableOptions);

        $this->addForeignKey('media_ibfk_1', '{{%media}}', 'ownerId', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%media}}');
    }
}
