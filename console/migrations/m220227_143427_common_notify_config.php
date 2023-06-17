<?php

use yii\db\Migration;

class m220227_143427_common_notify_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_notify_config}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'member_id' => "int(10) NULL DEFAULT '0' COMMENT '用户id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'name' => "varchar(100) NULL DEFAULT '' COMMENT '标识'",
            'title' => "varchar(100) NULL DEFAULT '' COMMENT '标题'",
            'content' => "text NULL COMMENT '内容'",
            'type' => "varchar(50) NULL DEFAULT '' COMMENT '发送类型'",
            'template_id' => "varchar(100) NULL DEFAULT '' COMMENT '模板ID'",
            'url' => "varchar(255) NULL DEFAULT '' COMMENT '跳转地址'",
            'params' => "json NULL COMMENT '参数'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'status' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_通知配置表'");
        
        /* 索引设置 */
        $this->createIndex('name','{{%common_notify_config}}','name',0);
        $this->createIndex('addon_name','{{%common_notify_config}}','addon_name',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_notify_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

