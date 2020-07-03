<?php

use yii\db\Migration;

class m200623_045755_006_create_table_auth_rule extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull()->append('PRIMARY KEY'),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->batchInsert('{{%auth_rule}}', ['name', 'data','created_at', 'updated_at'],
        [
            ['canDisableHist', 'O:27:"common\rbac\HistdisableRule":3:{s:4:"name";s:14:"canDisableHist";s:9:"createdAt";i:1592265282;s:9:"updatedAt";i:1592265282;}', 1592265282, 1592265282],
            ['canDisableMap', 'O:26:"common\rbac\MapdisableRule":3:{s:4:"name";s:13:"canDisableMap";s:9:"createdAt";i:1592284894;s:9:"updatedAt";i:1592284894;}', 1592284894, 1592284894],
            ['canDisableMedia', 'O:28:"common\rbac\MediadisableRule":3:{s:4:"name";s:15:"canDisableMedia";s:9:"createdAt";i:1592377429;s:9:"updatedAt";i:1592377429;}', 1592377429, 1592377429],
            ['isEditableHist', 'O:26:"common\rbac\HistupdateRule":3:{s:4:"name";s:14:"isEditableHist";s:9:"createdAt";i:1591156337;s:9:"updatedAt";i:1591156337;}', 1591156337, 1591156337],
            ['isEditableMap', 'O:25:"common\rbac\MapupdateRule":3:{s:4:"name";s:13:"isEditableMap";s:9:"createdAt";i:1591145243;s:9:"updatedAt";i:1591145243;}', 1591145243, 1591145243],
            ['isEditableMedia', 'O:27:"common\rbac\MediaupdateRule":3:{s:4:"name";s:15:"isEditableMedia";s:9:"createdAt";i:1592377457;s:9:"updatedAt";i:1592377457;}', 1592377457, 1592377457],
            ['isEditableProfile', 'O:29:"common\rbac\ProfileupdateRule":3:{s:4:"name";s:17:"isEditableProfile";s:9:"createdAt";i:1592794216;s:9:"updatedAt";i:1592794216;}', 1592794216, 1592794216]
        ]
       );
    }

    public function down()
    {
        $this->dropTable('{{%auth_rule}}');
    }
}
