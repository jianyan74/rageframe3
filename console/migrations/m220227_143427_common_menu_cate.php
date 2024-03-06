<?php

use yii\db\Migration;

class m220227_143427_common_menu_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_menu_cate}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'name' => "varchar(50) NULL DEFAULT '' COMMENT '标识'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'icon' => "varchar(50) NULL DEFAULT '' COMMENT 'icon'",
            'type' => "tinyint(4) NULL DEFAULT '0' COMMENT '应用中心'",
            'is_default_show' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '默认显示'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'addon_location' => "varchar(50) NULL DEFAULT '' COMMENT '插件显示位置'",
            'sort' => "int(10) NULL DEFAULT '999' COMMENT '排序'",
            'level' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'tree' => "varchar(300) NULL DEFAULT '' COMMENT '树'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'pattern' => "json NULL COMMENT '开发可见模式'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_菜单分类表'");

        /* 索引设置 */


        /* 表数据 */
        $this->insert('{{%common_menu_cate}}',['id'=>'1','title'=>'系统','name'=>'system','app_id'=>'backend','icon'=>'fa-cogs','type'=>'0','is_default_show'=>'0','is_addon'=>'0','addon_name'=>'','addon_location'=>'','sort'=>'9998','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'""','status'=>'1','created_at'=>'1633681071','updated_at'=>'1638170529']);
        $this->insert('{{%common_menu_cate}}',['id'=>'2','title'=>'应用','name'=>'addons','app_id'=>'backend','icon'=>'fa-th-large','type'=>'1','is_default_show'=>'0','is_addon'=>'0','addon_name'=>'','addon_location'=>'','sort'=>'9999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'""','status'=>'1','created_at'=>'1633681198','updated_at'=>'1640237234']);
        $this->insert('{{%common_menu_cate}}',['id'=>'3','title'=>'应用','name'=>'menuAddons','app_id'=>'merchant','icon'=>'fa-th-large','type'=>'1','is_default_show'=>'0','is_addon'=>'0','addon_name'=>'','addon_location'=>'','sort'=>'9999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'""','status'=>'1','created_at'=>'1638248633','updated_at'=>'1640237255']);
        $this->insert('{{%common_menu_cate}}',['id'=>'4','title'=>'系统更新','name'=>'','app_id'=>'backend','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'1','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1637394086']);
        $this->insert('{{%common_menu_cate}}',['id'=>'5','title'=>'系统更新','name'=>'','app_id'=>'frontend','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1635410281']);
        $this->insert('{{%common_menu_cate}}',['id'=>'6','title'=>'系统更新','name'=>'','app_id'=>'merchant','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1635410281']);
        $this->insert('{{%common_menu_cate}}',['id'=>'7','title'=>'系统更新','name'=>'','app_id'=>'html5','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1635410281']);
        $this->insert('{{%common_menu_cate}}',['id'=>'8','title'=>'系统更新','name'=>'','app_id'=>'api','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1635410281']);
        $this->insert('{{%common_menu_cate}}',['id'=>'9','title'=>'系统更新','name'=>'','app_id'=>'oauth2','icon'=>'fa fa-puzzle-piece','type'=>'0','is_default_show'=>'0','is_addon'=>'1','addon_name'=>'Authority','addon_location'=>'addons','sort'=>'999','level'=>'1','tree'=>'0-','pid'=>'0','pattern'=>'[]','status'=>'1','created_at'=>'1635410281','updated_at'=>'1635410281']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_menu_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

