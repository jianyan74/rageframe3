<?php

use yii\db\Migration;

class m220227_143427_common_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_menu}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NULL DEFAULT '' COMMENT '标题'",
            'name' => "varchar(50) NULL DEFAULT '' COMMENT '标识'",
            'app_id' => "varchar(20) NULL DEFAULT '' COMMENT '应用'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'addon_location' => "varchar(50) NULL DEFAULT '' COMMENT '插件显示位置'",
            'cate_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '分类id'",
            'pid' => "int(50) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'url' => "varchar(100) NULL DEFAULT '' COMMENT '路由'",
            'icon' => "varchar(50) NULL DEFAULT '' COMMENT '样式'",
            'level' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'dev' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '开发者[0:都可见;开发模式可见]'",
            'sort' => "int(10) NULL DEFAULT '999' COMMENT '排序'",
            'params' => "json NULL COMMENT '参数'",
            'pattern' => "json NULL COMMENT '开发可见模式'",
            'tree' => "varchar(300) NULL DEFAULT '' COMMENT '树'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_菜单导航表'");

        /* 索引设置 */
        $this->createIndex('url','{{%common_menu}}','url',0);


        /* 表数据 */
        $this->insert('{{%common_menu}}',['id'=>'1','title'=>'系统配置','name'=>'siteSettings','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'siteSettings','icon'=>'fa-cog','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633681980','updated_at'=>'1633681980']);
        $this->insert('{{%common_menu}}',['id'=>'2','title'=>'系统功能','name'=>'systemFunction','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'systemFunction','icon'=>'fa-list-ul','level'=>'1','dev'=>'1','sort'=>'1','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633682113','updated_at'=>'1633682113']);
        $this->insert('{{%common_menu}}',['id'=>'3','title'=>'菜单管理','name'=>'menu','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'2','url'=>'/common/menu/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-2-','status'=>'1','created_at'=>'1633682206','updated_at'=>'1633682206']);
        $this->insert('{{%common_menu}}',['id'=>'4','title'=>'配置管理','name'=>'config','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'2','url'=>'/common/config/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-2-','status'=>'1','created_at'=>'1633682206','updated_at'=>'1633682206']);
        $this->insert('{{%common_menu}}',['id'=>'5','title'=>'用户权限','name'=>'managerAuth','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'managerAuth','icon'=>'fa-user-secret','level'=>'1','dev'=>'0','sort'=>'2','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633682386','updated_at'=>'1633682386']);
        $this->insert('{{%common_menu}}',['id'=>'6','title'=>'管理员','name'=>'manager','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'5','url'=>'/manager/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-5-','status'=>'1','created_at'=>'1633682462','updated_at'=>'1633682462']);
        $this->insert('{{%common_menu}}',['id'=>'7','title'=>'角色管理','name'=>'managerAuthRole','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'5','url'=>'/auth-role/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-5-','status'=>'1','created_at'=>'1633682462','updated_at'=>'1633682462']);
        $this->insert('{{%common_menu}}',['id'=>'8','title'=>'权限管理','name'=>'managerAuthItem','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'5','url'=>'/auth-item/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-5-','status'=>'1','created_at'=>'1633682462','updated_at'=>'1633682462']);
        $this->insert('{{%common_menu}}',['id'=>'9','title'=>'开放授权','name'=>'oauth2Client','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'/oauth2/client/index','icon'=>'fa-shield-alt','level'=>'1','dev'=>'0','sort'=>'3','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633683033','updated_at'=>'1636076253']);
        $this->insert('{{%common_menu}}',['id'=>'10','title'=>'应用管理','name'=>'addons','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'/common/addons/index','icon'=>'fa-plug','level'=>'1','dev'=>'0','sort'=>'4','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633683090','updated_at'=>'1633683090']);
        $this->insert('{{%common_menu}}',['id'=>'11','title'=>'系统工具','name'=>'tool','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'tool','icon'=>'fa-microchip','level'=>'1','dev'=>'0','sort'=>'6','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1633683090','updated_at'=>'1633683090']);
        $this->insert('{{%common_menu}}',['id'=>'12','title'=>'省市区','name'=>'provinces','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'11','url'=>'/common/provinces/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-','status'=>'1','created_at'=>'1633687997','updated_at'=>'1633687997']);
        $this->insert('{{%common_menu}}',['id'=>'13','title'=>'资源文件','name'=>'attachment','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'11','url'=>'/common/attachment/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-','status'=>'1','created_at'=>'1633687997','updated_at'=>'1633687997']);
        $this->insert('{{%common_menu}}',['id'=>'14','title'=>'日志记录','name'=>'logs','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'11','url'=>'logs','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-','status'=>'1','created_at'=>'1633687997','updated_at'=>'1633687997']);
        $this->insert('{{%common_menu}}',['id'=>'16','title'=>'系统信息 ','name'=>'mainSystem','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'11','url'=>'/main/system','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-','status'=>'1','created_at'=>'1633687997','updated_at'=>'1634399719']);
        $this->insert('{{%common_menu}}',['id'=>'17','title'=>'短信日志','name'=>'smsLog','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'14','url'=>'/extend/sms-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-14-','status'=>'1','created_at'=>'1633688358','updated_at'=>'1633688358']);
        $this->insert('{{%common_menu}}',['id'=>'18','title'=>'支付日志','name'=>'payLog','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'14','url'=>'/extend/pay-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-14-','status'=>'1','created_at'=>'1633688358','updated_at'=>'1633688358']);
        $this->insert('{{%common_menu}}',['id'=>'19','title'=>'全局日志','name'=>'allLog','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'14','url'=>'/common/log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'1','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-14-','status'=>'1','created_at'=>'1633688358','updated_at'=>'1633688358']);
        $this->insert('{{%common_menu}}',['id'=>'20','title'=>'行为日志','name'=>'actionLog','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'14','url'=>'/common/action-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-11-14-','status'=>'1','created_at'=>'1633688358','updated_at'=>'1633688358']);
        $this->insert('{{%common_menu}}',['id'=>'21','title'=>'网站配置','name'=>'configEditAll','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'1','url'=>'/common/config/edit-all','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-1-','status'=>'1','created_at'=>'1633702413','updated_at'=>'1633702482']);
        $this->insert('{{%common_menu}}',['id'=>'22','title'=>'小票打印机','name'=>'extendReceiptPrinter','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'1','url'=>'/extend/receipt-printer/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-1-','status'=>'1','created_at'=>'1633702582','updated_at'=>'1633702582']);
        $this->insert('{{%common_menu}}',['id'=>'23','title'=>'公告消息','name'=>'notify','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'0','url'=>'','icon'=>'fa-comments','level'=>'1','dev'=>'0','sort'=>'5','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-','status'=>'1','created_at'=>'1640615837','updated_at'=>'1640676936']);
        $this->insert('{{%common_menu}}',['id'=>'24','title'=>'消息配置','name'=>'notifyConfig','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'23','url'=>'/common/notify-config/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-26-','status'=>'1','created_at'=>'1640615925','updated_at'=>'1640615941']);
        $this->insert('{{%common_menu}}',['id'=>'25','title'=>'公告管理','name'=>'notifyAnnounce','app_id'=>'backend','is_addon'=>'0','addon_name'=>'','addon_location'=>'','cate_id'=>'1','pid'=>'23','url'=>'/common/notify-announce/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','pattern'=>'""','tree'=>'0-26-','status'=>'1','created_at'=>'1640615925','updated_at'=>'1640616155']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_menu}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

