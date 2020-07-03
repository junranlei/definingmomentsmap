<?php

use yii\db\Migration;

class m200623_045755_013_create_table_auth_item extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull()->append('PRIMARY KEY'),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-auth_item-type', '{{%auth_item}}', 'type');
        $this->createIndex('rule_name', '{{%auth_item}}', 'rule_name');
        $this->addForeignKey('auth_item_ibfk_1', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE');
        
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
        [
            ['disableHist', '2', 'disable historical fact', 'canDisableHist', NULL, '1592265316', '1592265316'],
            ['disableMap', '2', 'disable map', 'canDisableMap', NULL, '1592284925', '1592284925'], 
            ['disableMedia', '2', 'disable media', 'canDisableMedia', NULL, '1592377486', '1592377486'], 
            ['SysAdmin', '1', 'System Administrator', NULL, NULL, '1589864593', '1589864637'], 
            ['SysAuthor', '1', 'System Author can edit and create.', NULL, NULL, '1589864629', '1592794256'], 
            ['updateHist', '2', 'update historical fact', 'isEditableHist', NULL, '1591156309', '1591156346'], 
            ['updateMap', '2', 'update map', 'isEditableMap', NULL, '1591145274', '1591145274'], 
            ['updateMedia', '2', 'update media', 'isEditableMedia', NULL, '1592377508', '1592377508'], 
            ['updateProfile', '2', 'update profile', 'isEditableProfile', NULL, '1592794247', '1592794247']
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%auth_item}}');
    }
}
