<?php

use yii\db\Migration;

class m220227_143429_rbac_auth_assignment extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%rbac_auth_assignment}}', [
            'role_id' => "int(11) NOT NULL COMMENT '角色'",
            'user_id' => "int(11) NOT NULL COMMENT '用户'",
            'app_id' => "varchar(20) NULL DEFAULT '' COMMENT '应用入口'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公用_会员授权角色表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%rbac_auth_assignment}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

