<?php

use yii\db\Migration;

class m230727_014510_addon_wechat_mini_live_goods_map extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_mini_live_goods_map}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'roomid' => "int(11) NULL DEFAULT '0' COMMENT '直播间ID'",
            'goods_id' => "int(11) NULL DEFAULT '0' COMMENT '商品id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信_小程序_直播商品关联'");
        
        /* 索引设置 */
        $this->createIndex('roomid','{{%addon_wechat_mini_live_goods_map}}','roomid',0);
        $this->createIndex('merchant_id','{{%addon_wechat_mini_live_goods_map}}','merchant_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_mini_live_goods_map}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

