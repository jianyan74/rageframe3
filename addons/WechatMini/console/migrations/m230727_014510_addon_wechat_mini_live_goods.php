<?php

use yii\db\Migration;

class m230727_014510_addon_wechat_mini_live_goods extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_mini_live_goods}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) NULL DEFAULT '0' COMMENT '门店ID'",
            'name' => "varchar(255) NULL DEFAULT '' COMMENT '商品名称'",
            'cover_img' => "varchar(255) NULL DEFAULT '' COMMENT '商品封面图链接'",
            'cover_media' => "varchar(200) NULL DEFAULT '' COMMENT '商品封面资源ID'",
            'url' => "varchar(255) NULL DEFAULT '' COMMENT '商品小程序路径'",
            'price' => "decimal(10,2) NULL COMMENT '商品价格(分)'",
            'price_two' => "decimal(10,2) NULL COMMENT '商品价格，使用方式看price_type'",
            'price_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '价格类型，1：一口价（只需要传入price，price2不传） 2：价格区间（price字段为左边界，price2字段为右边界，price和price2必传） 3：显示折扣价（price字段为原价，price2字段为现价， price和price2必传）'",
            'goods_id' => "int(11) NULL DEFAULT '0' COMMENT '商品id'",
            'explain_url' => "varchar(255) NULL COMMENT '商品讲解视频'",
            'third_party_appid' => "varchar(50) NULL DEFAULT '' COMMENT '第三方商品appid ,当前小程序商品则为空'",
            'third_party_tag' => "tinyint(4) NULL DEFAULT '0' COMMENT '1、2：表示是为 API 添加商品，否则是直播控制台添加的商品'",
            'audit_status' => "tinyint(4) NULL DEFAULT '0' COMMENT '0：未审核，1：审核中，2:审核通过，3审核失败'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信_小程序_直播商品'");
        
        /* 索引设置 */
        $this->createIndex('merchant_id','{{%addon_wechat_mini_live_goods}}','merchant_id',0);
        $this->createIndex('goods_id','{{%addon_wechat_mini_live_goods}}','goods_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_mini_live_goods}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

