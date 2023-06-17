<?php

use yii\db\Migration;

class m220227_143426_common_action_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_action_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(10) NULL DEFAULT '0' COMMENT '用户id'",
            'member_name' => "varchar(100) NULL DEFAULT '' COMMENT '用户id'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块'",
            'controller' => "varchar(100) NULL DEFAULT '' COMMENT '控制器'",
            'action' => "varchar(50) NULL DEFAULT '' COMMENT '方法'",
            'url' => "varchar(255) NULL DEFAULT '' COMMENT '提交url'",
            'get_data' => "json NULL COMMENT 'get数据'",
            'post_data' => "json NULL COMMENT 'post数据'",
            'header_data' => "json NULL COMMENT 'header数据'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'remark' => "varchar(1000) NULL DEFAULT '' COMMENT '日志备注'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'map_id' => "int(11) NULL DEFAULT '0' COMMENT '关联ID'",
            'map_data' => "json NULL COMMENT '关联数据'",
            'country' => "varchar(100) NULL DEFAULT '' COMMENT '国家'",
            'provinces' => "varchar(100) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(100) NULL DEFAULT '' COMMENT '城市'",
            'device' => "varchar(200) NULL DEFAULT '' COMMENT '设备信息'",
            'ip' => "varchar(40) NULL DEFAULT '' COMMENT 'ip地址'",
            'req_id' => "varchar(50) NULL DEFAULT '' COMMENT '对外id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_行为表'");

        /* 索引设置 */
        $this->createIndex('addon_name','{{%common_action_log}}','addon_name',0);
        $this->createIndex('is_addon','{{%common_action_log}}','is_addon, status',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_action_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

