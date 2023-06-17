<?php

use yii\db\Migration;

class m220227_143427_common_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_log}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) NULL DEFAULT '0' COMMENT '用户id'",
            'member_name' => "varchar(100) NULL DEFAULT '' COMMENT '用户名称'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块'",
            'controller' => "varchar(100) NULL DEFAULT '' COMMENT '控制器'",
            'action' => "varchar(50) NULL DEFAULT '' COMMENT '方法'",
            'url' => "varchar(1000) NULL DEFAULT '' COMMENT '提交url'",
            'get_data' => "json NULL COMMENT 'get数据'",
            'post_data' => "json NULL COMMENT 'post数据'",
            'header_data' => "json NULL COMMENT 'header数据'",
            'ip' => "varchar(50) NULL DEFAULT '' COMMENT 'ip地址'",
            'error_code' => "int(10) NULL DEFAULT '0' COMMENT '报错code'",
            'error_msg' => "varchar(1000) NULL DEFAULT '' COMMENT '报错信息'",
            'error_data' => "json NULL COMMENT '报错日志'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'req_id' => "varchar(50) NULL DEFAULT '' COMMENT '对外id'",
            'device' => "varchar(200) NULL DEFAULT '' COMMENT '设备信息'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_日志'");

        /* 索引设置 */
        $this->createIndex('error_code','{{%common_log}}','error_code',0);
        $this->createIndex('req_id','{{%common_log}}','req_id',0);
        $this->createIndex('ip','{{%common_log}}','ip',0);
        $this->createIndex('created_at','{{%common_log}}','created_at',0);
        $this->createIndex('status','{{%common_log}}','status, created_at',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

