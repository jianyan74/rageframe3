<?php

use yii\db\Migration;

class m220608_083043_addon_tiny_blog_adv extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_tiny_blog_adv}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '序号'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺id'",
            'name' => "varchar(30) NOT NULL DEFAULT '' COMMENT '标题'",
            'cover' => "varchar(100) NULL DEFAULT '' COMMENT '图片'",
            'location_id' => "int(11) NULL DEFAULT '0' COMMENT '广告位ID'",
            'silder_text' => "varchar(150) NULL DEFAULT '' COMMENT '图片描述'",
            'start_time' => "int(10) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(10) NULL DEFAULT '0' COMMENT '结束时间'",
            'jump_link' => "varchar(255) NULL DEFAULT '' COMMENT '跳转链接'",
            'jump_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '跳转方式[1:新标签; 2:当前页]'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '优先级'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='扩展_博客_幻灯片'");

        /* 索引设置 */
        $this->createIndex('start_time','{{%addon_tiny_blog_adv}}','start_time, end_time',0);
        $this->createIndex('merchant_id','{{%addon_tiny_blog_adv}}','merchant_id',0);
        $this->createIndex('status','{{%addon_tiny_blog_adv}}','status',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_tiny_blog_adv}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

