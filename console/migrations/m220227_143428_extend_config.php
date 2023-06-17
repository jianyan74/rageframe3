<?php

use yii\db\Migration;

class m220227_143428_extend_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%extend_config}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'store_id' => "int(10) NULL DEFAULT '0' COMMENT '门店'",
            'title' => "varchar(50) NULL DEFAULT '' COMMENT '配置标题'",
            'name' => "varchar(50) NULL DEFAULT '' COMMENT '配置标识'",
            'type' => "varchar(30) NULL DEFAULT '' COMMENT '配置类型'",
            'remark' => "varchar(1000) NULL DEFAULT '' COMMENT '说明'",
            'data' => "json NULL COMMENT '配置'",
            'sort' => "int(10) unsigned NULL DEFAULT '0' COMMENT '排序'",
            'extend' => "int(10) NULL DEFAULT '0' COMMENT '扩展字段'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='扩展_配置表'");
        
        /* 索引设置 */
        $this->createIndex('type','{{%extend_config}}','type',0);
        $this->createIndex('uk_name','{{%extend_config}}','name',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%extend_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

