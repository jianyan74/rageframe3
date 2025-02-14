<?php

use yii\db\Migration;

class m220227_143427_common_addons extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_addons}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(20) NOT NULL DEFAULT '' COMMENT '中文名'",
            'name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名或标识'",
            'title_initial' => "varchar(1) NOT NULL DEFAULT '' COMMENT '首字母拼音'",
            'bootstrap' => "varchar(255) NULL DEFAULT '' COMMENT '启用文件'",
            'service' => "varchar(255) NULL DEFAULT '' COMMENT '服务调用类'",
            'cover' => "varchar(200) NULL DEFAULT '' COMMENT '封面'",
            'group' => "varchar(20) NULL DEFAULT '' COMMENT '组别'",
            'brief_introduction' => "varchar(140) NULL DEFAULT '' COMMENT '简单介绍'",
            'description' => "varchar(1000) NULL DEFAULT '' COMMENT '插件描述'",
            'author' => "varchar(40) NULL DEFAULT '' COMMENT '作者'",
            'version' => "varchar(20) NULL DEFAULT '1.0.0' COMMENT '版本号'",
            'is_merchant_route_map' => "tinyint(1) NULL DEFAULT '0' COMMENT '商户路由映射'",
            'default_config' => "json NULL COMMENT '默认配置'",
            'console' => "json NULL COMMENT '控制台'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_插件表'");

        /* 索引设置 */
        $this->createIndex('name','{{%common_addons}}','name',0);
        $this->createIndex('update','{{%common_addons}}','updated_at',0);


        /* 表数据 */
        $this->insert('{{%common_addons}}',['id'=>'1','title'=>'系统更新','name'=>'Authority','title_initial'=>'X','bootstrap'=>'addons\\Authority\\common\\components\\Bootstrap','service'=>'addons\\Authority\\services\\Application','cover'=>'','group'=>'business','brief_introduction'=>'RageFrame 官方在线升级工具','description'=>'','author'=>'简言','version'=>'3.1.53','is_merchant_route_map'=>'0','default_config'=>'[]','console'=>'[]','status'=>'1','created_at'=>'1635410282','updated_at'=>'1635410282']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_addons}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

