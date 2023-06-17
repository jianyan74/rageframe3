<?php

use yii\db\Migration;

class m220227_143429_oauth2_authorization_code extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%oauth2_authorization_code}}', [
            'authorization_code' => "varchar(100) NOT NULL",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'client_id' => "varchar(64) NOT NULL COMMENT '授权ID'",
            'member_id' => "varchar(100) NULL COMMENT '用户ID'",
            'redirect_uri' => "varchar(2000) NULL COMMENT '回调url'",
            'expires' => "timestamp NOT NULL COMMENT '有效期'",
            'scope' => "json NULL COMMENT '授权权限'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`authorization_code`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='oauth2_授权回调code'");
        
        /* 索引设置 */
        $this->createIndex('authorization_code','{{%oauth2_authorization_code}}','authorization_code',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%oauth2_authorization_code}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

