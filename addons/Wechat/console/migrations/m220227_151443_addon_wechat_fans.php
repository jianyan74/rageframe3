<?php

use yii\db\Migration;

class m220227_151443_addon_wechat_fans extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_wechat_fans}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户ID'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'unionid' => "varchar(64) NULL DEFAULT '' COMMENT '唯一公众号ID'",
            'openid' => "varchar(50) NOT NULL DEFAULT '' COMMENT 'openid'",
            'nickname' => "varchar(50) NULL DEFAULT '' COMMENT '昵称'",
            'head_portrait' => "varchar(255) NULL DEFAULT '' COMMENT '头像'",
            'follow' => "tinyint(1) NULL DEFAULT '1' COMMENT '是否关注[1:关注;0:取消关注]'",
            'follow_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '关注时间'",
            'unfollow_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '取消关注时间'",
            'group_id' => "int(10) NULL DEFAULT '0' COMMENT '分组id'",
            'tag' => "json NULL COMMENT '标签'",
            'last_longitude' => "varchar(10) NULL DEFAULT '' COMMENT '最近经纬度上报'",
            'last_latitude' => "varchar(10) NULL DEFAULT '' COMMENT '最近经纬度上报'",
            'last_address' => "varchar(100) NULL DEFAULT '' COMMENT '最近经纬度上报地址'",
            'last_updated' => "int(10) NULL DEFAULT '0' COMMENT '最后更新时间'",
            'remark' => "varchar(30) NULL DEFAULT '' COMMENT '粉丝备注'",
            'subscribe_scene' => "varchar(50) NULL DEFAULT '' COMMENT '关注来源'",
            'qr_scene' => "varchar(32) NULL DEFAULT '' COMMENT '二维码扫码场景'",
            'qr_scene_str' => "varchar(64) NULL DEFAULT '' COMMENT '二维码扫码场景描述'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='微信_粉丝表'");

        /* 索引设置 */
        $this->createIndex('openid','{{%addon_wechat_fans}}','openid',0);
        $this->createIndex('nickname','{{%addon_wechat_fans}}','nickname',0);
        $this->createIndex('member_id','{{%addon_wechat_fans}}','member_id',0);
        $this->createIndex('unionid','{{%addon_wechat_fans}}','unionid',0);
        $this->createIndex('subscribe_scene','{{%addon_wechat_fans}}','subscribe_scene',0);
        $this->createIndex('follow_time','{{%addon_wechat_fans}}','follow_time',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_fans}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

