<?php

use yii\db\Migration;

class m230727_014510_addon_wechat_mini_live extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_wechat_mini_live}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '组合商品id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'name' => "varchar(200) NULL DEFAULT '' COMMENT '直播间名称'",
            'roomid' => "int(11) NULL DEFAULT '0' COMMENT '直播间ID'",
            'cover_img' => "varchar(200) NULL DEFAULT '' COMMENT '直播封面'",
            'share_img' => "varchar(200) NULL DEFAULT '' COMMENT '直播间分享图链接'",
            'live_status' => "int(10) NULL DEFAULT '102' COMMENT '直播间状态'",
            'start_time' => "int(11) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(11) NULL DEFAULT '0' COMMENT '结束时间'",
            'anchor_name' => "varchar(200) NULL DEFAULT '' COMMENT '主播名'",
            'anchor_wechat' => "varchar(50) NULL DEFAULT '' COMMENT '主播微信'",
            'sub_anchor_wechat' => "varchar(50) NULL DEFAULT '' COMMENT '主播副号微信号'",
            'live_type' => "tinyint(4) NULL DEFAULT '0' COMMENT '直播类型，1 推流 0 手机直播'",
            'creater_openid' => "varchar(50) NULL DEFAULT '' COMMENT '创建者openid'",
            'creater_wechat' => "varchar(50) NULL DEFAULT '' COMMENT '创建者微信号'",
            'close_like' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭点赞[0:开启;1:关闭]'",
            'close_goods' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭货架[0:开启;1:关闭]'",
            'close_comment' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭评论[0:开启;1:关闭]'",
            'close_share' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭分享[0:开启;1:关闭]'",
            'close_kf' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭客服[0:开启;1:关闭]'",
            'close_replay' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否关闭回放[0:开启;1:关闭]'",
            'is_feeds_public' => "tinyint(4) NULL DEFAULT '1' COMMENT '是否开启官方收录[1:开启;0:关闭]'",
            'feeds_img' => "varchar(200) NULL DEFAULT '' COMMENT '官方收录封面'",
            'total' => "int(10) unsigned NULL DEFAULT '0' COMMENT '拉取房间总数'",
            'playback' => "json NULL COMMENT '回放视频'",
            'push_addr' => "varchar(500) NULL DEFAULT '' COMMENT '直播间推流地址'",
            'assistant' => "json NULL COMMENT '小助手'",
            'cover_media' => "varchar(200) NULL DEFAULT '' COMMENT '直播封面资源ID'",
            'share_media' => "varchar(200) NULL DEFAULT '' COMMENT '直播间分享图资源ID'",
            'feeds_media' => "varchar(200) NULL DEFAULT '' COMMENT '官方收录封面资源ID'",
            'share_path' => "json NULL COMMENT '分享'",
            'qrcode_url' => "varchar(255) NULL DEFAULT '' COMMENT '二维码地址'",
            'is_recommend' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否推荐'",
            'is_stick' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否置顶'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='微信_小程序_直播'");

        /* 索引设置 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_mini_live}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

