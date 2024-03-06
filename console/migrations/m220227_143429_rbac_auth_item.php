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
        $this->insert('{{%rbac_auth_item}}',['id'=>'1','name'=>'/*','title'=>'所有权限','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'0','addon_name'=>'','sort'=>'0','tree'=>'0-','status'=>'1','created_at'=>'1645971795','updated_at'=>'1687156501']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'2','name'=>'/authority/*','title'=>'所有权限','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'1','addon_name'=>'Authority','sort'=>'9999','tree'=>'0-','status'=>'1','created_at'=>'1686990616','updated_at'=>'1686990616']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'3','name'=>'menuCate:1','title'=>'系统','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'0','addon_name'=>'','sort'=>'10001','tree'=>'0-','status'=>'1','created_at'=>'1687154396','updated_at'=>'1687156514']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'4','name'=>'menuCate:2','title'=>'应用中心','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'0','addon_name'=>'','sort'=>'10002','tree'=>'0-','status'=>'1','created_at'=>'1636956327','updated_at'=>'1687156499']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'5','name'=>'siteSettings','title'=>'系统配置','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155149','updated_at'=>'1687155149']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'6','name'=>'systemFunction','title'=>'系统功能','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155163','updated_at'=>'1687155163']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'7','name'=>'managerAuth','title'=>'用户权限','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155177','updated_at'=>'1687155177']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'8','name'=>'/oauth2/client/*','title'=>'开放授权','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155190','updated_at'=>'1687155190']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'9','name'=>'/common/addons/*','title'=>'应用管理','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155204','updated_at'=>'1687155204']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'10','name'=>'notify','title'=>'消息公告','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155217','updated_at'=>'1687155217']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'11','name'=>'tool','title'=>'系统工具','app_id'=>'backend','pid'=>'3','level'=>'2','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-','status'=>'1','created_at'=>'1687155227','updated_at'=>'1687156079']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'12','name'=>'/common/config/edit-all','title'=>'网站配置','app_id'=>'backend','pid'=>'5','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-5-','status'=>'1','created_at'=>'1687155429','updated_at'=>'1687155429']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'13','name'=>'/common/config/update-info','title'=>'配置保存','app_id'=>'backend','pid'=>'5','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-5-','status'=>'1','created_at'=>'1687155448','updated_at'=>'1687155448']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'14','name'=>'/extend/receipt-printer/*','title'=>'小票打印机','app_id'=>'backend','pid'=>'5','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-5-','status'=>'1','created_at'=>'1687155488','updated_at'=>'1687155488']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'15','name'=>'/common/menu/*','title'=>'菜单管理','app_id'=>'backend','pid'=>'6','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-6-','status'=>'1','created_at'=>'1687155508','updated_at'=>'1687155508']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'16','name'=>'/common/menu-cate/*','title'=>'菜单分类','app_id'=>'backend','pid'=>'6','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-6-','status'=>'1','created_at'=>'1687155521','updated_at'=>'1687155521']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'17','name'=>'/common/config/*','title'=>'配置管理','app_id'=>'backend','pid'=>'6','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-6-','status'=>'1','created_at'=>'1687155535','updated_at'=>'1687155535']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'18','name'=>'/common/config-cate/*','title'=>'配置分类','app_id'=>'backend','pid'=>'6','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-6-','status'=>'1','created_at'=>'1687155549','updated_at'=>'1687155549']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'19','name'=>'/manager/*','title'=>'管理员','app_id'=>'backend','pid'=>'7','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-7-','status'=>'1','created_at'=>'1687155607','updated_at'=>'1687155607']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'20','name'=>'/auth-role/*','title'=>'角色管理','app_id'=>'backend','pid'=>'7','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-7-','status'=>'1','created_at'=>'1687155619','updated_at'=>'1687155619']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'21','name'=>'/auth-item/*','title'=>'权限管理','app_id'=>'backend','pid'=>'7','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-7-','status'=>'1','created_at'=>'1687155630','updated_at'=>'1687155630']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'22','name'=>'/common/notify-config/*','title'=>'消息配置','app_id'=>'backend','pid'=>'10','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-10-','status'=>'1','created_at'=>'1687156042','updated_at'=>'1687156042']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'23','name'=>'/common/notify-announce/*','title'=>'公告管理','app_id'=>'backend','pid'=>'10','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-10-','status'=>'1','created_at'=>'1687156063','updated_at'=>'1687156063']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'24','name'=>'/common/provinces/*','title'=>'省市区','app_id'=>'backend','pid'=>'11','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-','status'=>'1','created_at'=>'1687156092','updated_at'=>'1687156092']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'25','name'=>'/common/attachment/*','title'=>'素材管理','app_id'=>'backend','pid'=>'11','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-','status'=>'1','created_at'=>'1687156108','updated_at'=>'1687156108']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'26','name'=>'/common/attachment-cate/*','title'=>'素材分类','app_id'=>'backend','pid'=>'11','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-','status'=>'1','created_at'=>'1687156123','updated_at'=>'1687156123']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'27','name'=>'logs','title'=>'日志记录','app_id'=>'backend','pid'=>'11','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-','status'=>'1','created_at'=>'1687156139','updated_at'=>'1687156139']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'28','name'=>'/extend/sms-log/*','title'=>'短信日志','app_id'=>'backend','pid'=>'27','level'=>'4','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-27-','status'=>'1','created_at'=>'1687156157','updated_at'=>'1687156157']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'29','name'=>'/extend/pay-log/*','title'=>'支付日志','app_id'=>'backend','pid'=>'27','level'=>'4','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-27-','status'=>'1','created_at'=>'1687156169','updated_at'=>'1687156169']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'30','name'=>'/common/action-log/*','title'=>'行为日志','app_id'=>'backend','pid'=>'27','level'=>'4','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-27-','status'=>'1','created_at'=>'1687156181','updated_at'=>'1687156181']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'31','name'=>'/common/log/*','title'=>'全局日志','app_id'=>'backend','pid'=>'27','level'=>'4','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-27-','status'=>'1','created_at'=>'1687156208','updated_at'=>'1687156208']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'32','name'=>'/main/system','title'=>'系统信息','app_id'=>'backend','pid'=>'11','level'=>'3','is_addon'=>'0','addon_name'=>'','sort'=>'9999','tree'=>'0-3-11-','status'=>'1','created_at'=>'1687156226','updated_at'=>'1687156226']);
        $this->insert('{{%rbac_auth_item}}',['id'=>'33','name'=>'/notify/*','title'=>'系统通知','app_id'=>'backend','pid'=>'0','level'=>'1','is_addon'=>'0','addon_name'=>'','sort'=>'10000','tree'=>'0-','status'=>'1','created_at'=>'1687156394','updated_at'=>'1687156515']);

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

