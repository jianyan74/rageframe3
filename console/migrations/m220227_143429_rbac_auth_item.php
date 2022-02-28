<?php

use yii\db\Migration;

class m220227_143429_rbac_auth_item extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%rbac_auth_item}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'name' => "varchar(64) NOT NULL DEFAULT '' COMMENT '别名'",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '标题'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'pid' => "int(10) NULL DEFAULT '0' COMMENT '父级id'",
            'level' => "int(5) NULL DEFAULT '1' COMMENT '级别'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'sort' => "int(10) NULL DEFAULT '9999' COMMENT '排序'",
            'tree' => "varchar(500) NULL DEFAULT '' COMMENT '树'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(11) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(11) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_权限表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%rbac_auth_item}}',['id'=>'1','name'=>'/*','title'=>'所有权限','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0','status'=>'1','created_at'=>'1645971795','updated_at'=>'1645971795']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%rbac_auth_item}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

