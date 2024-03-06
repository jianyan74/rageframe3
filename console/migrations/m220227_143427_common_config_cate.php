<?php

use yii\db\Migration;

class m220227_143427_common_config_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_config_cate}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'name' => "varchar(100) NULL DEFAULT '' COMMENT '标识'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'level' => "tinyint(1) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'sort' => "int(5) NULL DEFAULT '0' COMMENT '排序'",
            'tree' => "varchar(300) NOT NULL DEFAULT '' COMMENT '树'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_配置分类表'");

        /* 索引设置 */


        /* 表数据 */
        $this->insert('{{%common_config_cate}}',['id'=>'1','title'=>'网站配置','name'=>'site','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'0','tree'=>'0-','status'=>'1','created_at'=>'1553908350','updated_at'=>'1634397053']);
        $this->insert('{{%common_config_cate}}',['id'=>'2','title'=>'系统配置','name'=>'system','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'1','tree'=>'0-','status'=>'1','created_at'=>'1553908371','updated_at'=>'1634397074']);
        $this->insert('{{%common_config_cate}}',['id'=>'3','title'=>'微信配置','name'=>'wechat','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'3','tree'=>'0-','status'=>'1','created_at'=>'1553908392','updated_at'=>'1634397041']);
        $this->insert('{{%common_config_cate}}',['id'=>'4','title'=>'支付配置','name'=>'pay','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'5','tree'=>'0-','status'=>'1','created_at'=>'1553908403','updated_at'=>'1634397221']);
        $this->insert('{{%common_config_cate}}',['id'=>'5','title'=>'第三方授权','name'=>'thirdPartyOAuth2','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'6','tree'=>'0-','status'=>'1','created_at'=>'1553908415','updated_at'=>'1634397799']);
        $this->insert('{{%common_config_cate}}',['id'=>'6','title'=>'邮件配置','name'=>'mail','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'7','tree'=>'0-','status'=>'1','created_at'=>'1553908421','updated_at'=>'1634397520']);
        $this->insert('{{%common_config_cate}}',['id'=>'7','title'=>'云存储','name'=>'cloudStorage','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'9','tree'=>'0-','status'=>'1','created_at'=>'1553908432','updated_at'=>'1634397646']);
        $this->insert('{{%common_config_cate}}',['id'=>'8','title'=>'支付宝','name'=>'payAli','pid'=>'4','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-4-','status'=>'1','created_at'=>'1553908441','updated_at'=>'1634397232']);
        $this->insert('{{%common_config_cate}}',['id'=>'9','title'=>'微信','name'=>'payWechat','pid'=>'4','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-4-','status'=>'1','created_at'=>'1553908448','updated_at'=>'1634397244']);
        $this->insert('{{%common_config_cate}}',['id'=>'10','title'=>'银联','name'=>'payUnion','pid'=>'4','app_id'=>'backend','level'=>'2','sort'=>'2','tree'=>'0-4-','status'=>'1','created_at'=>'1553908458','updated_at'=>'1634397348']);
        $this->insert('{{%common_config_cate}}',['id'=>'11','title'=>'QQ授权','name'=>'thirdPartyOAuth2QQ','pid'=>'5','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-5-','status'=>'1','created_at'=>'1553908474','updated_at'=>'1634397812']);
        $this->insert('{{%common_config_cate}}',['id'=>'12','title'=>'微博授权','name'=>'thirdPartyOAuth2Weibo','pid'=>'5','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-5-','status'=>'1','created_at'=>'1553908487','updated_at'=>'1634397820']);
        $this->insert('{{%common_config_cate}}',['id'=>'13','title'=>'微信授权','name'=>'thirdPartyOAuth2Wechat','pid'=>'5','app_id'=>'backend','level'=>'2','sort'=>'2','tree'=>'0-5-','status'=>'1','created_at'=>'1553908497','updated_at'=>'1634397827']);
        $this->insert('{{%common_config_cate}}',['id'=>'14','title'=>'GitHub授权','name'=>'thirdPartyOAuth2GitHub','pid'=>'5','app_id'=>'backend','level'=>'2','sort'=>'3','tree'=>'0-5-','status'=>'1','created_at'=>'1553908526','updated_at'=>'1634397838']);
        $this->insert('{{%common_config_cate}}',['id'=>'15','title'=>'七牛云','name'=>'cloudStorageQiniu','pid'=>'7','app_id'=>'backend','level'=>'2','sort'=>'3','tree'=>'0-7-','status'=>'1','created_at'=>'1553908544','updated_at'=>'1634397692']);
        $this->insert('{{%common_config_cate}}',['id'=>'16','title'=>'邮件','name'=>'mailBase','pid'=>'6','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-6-','status'=>'1','created_at'=>'1553908565','updated_at'=>'1634397530']);
        $this->insert('{{%common_config_cate}}',['id'=>'17','title'=>'网站基础','name'=>'siteBase','pid'=>'1','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-1-','status'=>'1','created_at'=>'1553908574','updated_at'=>'1634397061']);
        $this->insert('{{%common_config_cate}}',['id'=>'18','title'=>'系统基础','name'=>'systemBase','pid'=>'2','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-2-','status'=>'1','created_at'=>'1553908618','updated_at'=>'1634397084']);
        $this->insert('{{%common_config_cate}}',['id'=>'19','title'=>'公众号','name'=>'wechatOfficialAccounts','pid'=>'3','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-3-','status'=>'1','created_at'=>'1553908626','updated_at'=>'1634397377']);
        $this->insert('{{%common_config_cate}}',['id'=>'20','title'=>'阿里云OSS','name'=>'cloudStorageOSS','pid'=>'7','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-7-','status'=>'1','created_at'=>'1553908635','updated_at'=>'1634397672']);
        $this->insert('{{%common_config_cate}}',['id'=>'21','title'=>'小程序','name'=>'miniProgram','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'3','tree'=>'0-','status'=>'1','created_at'=>'1553908673','updated_at'=>'1634397181']);
        $this->insert('{{%common_config_cate}}',['id'=>'22','title'=>'微信','name'=>'miniProgramWechat','pid'=>'21','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-21-','status'=>'1','created_at'=>'1553908719','updated_at'=>'1634397211']);
        $this->insert('{{%common_config_cate}}',['id'=>'23','title'=>'图片处理','name'=>'systemImage','pid'=>'2','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-2-','status'=>'1','created_at'=>'1553908747','updated_at'=>'1634397154']);
        $this->insert('{{%common_config_cate}}',['id'=>'24','title'=>'App推送','name'=>'appPlus','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'12','tree'=>'0-','status'=>'1','created_at'=>'1553908754','updated_at'=>'1634397775']);
        $this->insert('{{%common_config_cate}}',['id'=>'25','title'=>'极光推送','name'=>'appPlusJPlus','pid'=>'24','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-24-','status'=>'1','created_at'=>'1553908769','updated_at'=>'1634397881']);
        $this->insert('{{%common_config_cate}}',['id'=>'27','title'=>'短信配置','name'=>'sms','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'8','tree'=>'0-','status'=>'1','created_at'=>'1559260477','updated_at'=>'1634397540']);
        $this->insert('{{%common_config_cate}}',['id'=>'28','title'=>'阿里云','name'=>'smsAli','pid'=>'27','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-27-','status'=>'1','created_at'=>'1559260496','updated_at'=>'1634397566']);
        $this->insert('{{%common_config_cate}}',['id'=>'29','title'=>'地图','name'=>'map','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'11','tree'=>'0-','status'=>'1','created_at'=>'1559402417','updated_at'=>'1634397759']);
        $this->insert('{{%common_config_cate}}',['id'=>'30','title'=>'百度地图','name'=>'mapBaiDu','pid'=>'29','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-29-','status'=>'1','created_at'=>'1559402426','updated_at'=>'1634974854']);
        $this->insert('{{%common_config_cate}}',['id'=>'31','title'=>'腾讯地图','name'=>'mapTencent','pid'=>'29','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-29-','status'=>'1','created_at'=>'1559402436','updated_at'=>'1634974873']);
        $this->insert('{{%common_config_cate}}',['id'=>'32','title'=>'高德地图','name'=>'mapAmap','pid'=>'29','app_id'=>'backend','level'=>'2','sort'=>'3','tree'=>'0-29-','status'=>'1','created_at'=>'1559402447','updated_at'=>'1634974910']);
        $this->insert('{{%common_config_cate}}',['id'=>'33','title'=>'腾讯COS','name'=>'cloudStorageCOS','pid'=>'7','app_id'=>'backend','level'=>'2','sort'=>'2','tree'=>'0-7-','status'=>'1','created_at'=>'1559527993','updated_at'=>'1634397680']);
        $this->insert('{{%common_config_cate}}',['id'=>'34','title'=>'OAuth2','name'=>'OAuth2','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'13','tree'=>'0-','status'=>'1','created_at'=>'1559704928','updated_at'=>'1634397851']);
        $this->insert('{{%common_config_cate}}',['id'=>'35','title'=>'授权配置','name'=>'OAuth2Base','pid'=>'34','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-34-','status'=>'1','created_at'=>'1559704944','updated_at'=>'1634397866']);
        $this->insert('{{%common_config_cate}}',['id'=>'43','title'=>'物流追踪','name'=>'logistics','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'10','tree'=>'0-','status'=>'1','created_at'=>'1575892976','updated_at'=>'1634397706']);
        $this->insert('{{%common_config_cate}}',['id'=>'44','title'=>'快递鸟','name'=>'logisticsKuaiDiNiao','pid'=>'43','app_id'=>'backend','level'=>'2','sort'=>'2','tree'=>'0-43-','status'=>'1','created_at'=>'1575892983','updated_at'=>'1634974814']);
        $this->insert('{{%common_config_cate}}',['id'=>'45','title'=>'快递100','name'=>'logisticsKuaiDi100','pid'=>'43','app_id'=>'backend','level'=>'2','sort'=>'3','tree'=>'0-43-','status'=>'1','created_at'=>'1575892995','updated_at'=>'1634974824']);
        $this->insert('{{%common_config_cate}}',['id'=>'46','title'=>'阿里云','name'=>'logisticsAli','pid'=>'43','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-43-','status'=>'1','created_at'=>'1575893861','updated_at'=>'1634397741']);
        $this->insert('{{%common_config_cate}}',['id'=>'47','title'=>'聚合','name'=>'logisticsJuHe','pid'=>'43','app_id'=>'backend','level'=>'2','sort'=>'4','tree'=>'0-43-','status'=>'1','created_at'=>'1575987657','updated_at'=>'1634974838']);
        $this->insert('{{%common_config_cate}}',['id'=>'52','title'=>'个推推送','name'=>'appPlusGeTui','pid'=>'24','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-24-','status'=>'1','created_at'=>'1589641827','updated_at'=>'1634974940']);
        $this->insert('{{%common_config_cate}}',['id'=>'55','title'=>'Stripe','name'=>'payStripe','pid'=>'4','app_id'=>'backend','level'=>'2','sort'=>'3','tree'=>'0-4-','status'=>'1','created_at'=>'1594103281','updated_at'=>'1634397265']);
        $this->insert('{{%common_config_cate}}',['id'=>'60','title'=>'字节跳动','name'=>'miniProgramByteDance','pid'=>'21','app_id'=>'backend','level'=>'2','sort'=>'1','tree'=>'0-21-','status'=>'1','created_at'=>'1619492775','updated_at'=>'1634397316']);
        $this->insert('{{%common_config_cate}}',['id'=>'61','title'=>'字节跳动','name'=>'payByteDance','pid'=>'4','app_id'=>'backend','level'=>'2','sort'=>'4','tree'=>'0-4-','status'=>'1','created_at'=>'1619493764','updated_at'=>'1634397329']);
        $this->insert('{{%common_config_cate}}',['id'=>'62','title'=>'默认驱动','name'=>'cloudStorageDefault','pid'=>'7','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-7-','status'=>'1','created_at'=>'1619929798','updated_at'=>'1634397660']);
        $this->insert('{{%common_config_cate}}',['id'=>'63','title'=>'默认物流追踪','name'=>'logisticsDefault','pid'=>'43','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-43-','status'=>'1','created_at'=>'1619936881','updated_at'=>'1634397718']);
        $this->insert('{{%common_config_cate}}',['id'=>'64','title'=>'默认短信','name'=>'smsDefault','pid'=>'27','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-27-','status'=>'1','created_at'=>'1619937935','updated_at'=>'1634397553']);
        $this->insert('{{%common_config_cate}}',['id'=>'65','title'=>'腾讯云','name'=>'smsTencent','pid'=>'27','app_id'=>'backend','level'=>'2','sort'=>'2','tree'=>'0-27-','status'=>'1','created_at'=>'1619937948','updated_at'=>'1634397582']);
        $this->insert('{{%common_config_cate}}',['id'=>'83','title'=>'限流配置','name'=>'currentLimiting','pid'=>'0','app_id'=>'backend','level'=>'1','sort'=>'14','tree'=>'0-','status'=>'1','created_at'=>'1635566120','updated_at'=>'1635566120']);
        $this->insert('{{%common_config_cate}}',['id'=>'84','title'=>'基础限流','name'=>'currentLimitingBase','pid'=>'83','app_id'=>'backend','level'=>'2','sort'=>'0','tree'=>'0-83-','status'=>'1','created_at'=>'1635566154','updated_at'=>'1635566172']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_config_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

