<?php

use yii\db\Migration;

class m220608_083043_addon_tiny_blog_friendly_link extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_tiny_blog_friendly_link}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺id'",
            'title' => "varchar(50) NOT NULL COMMENT '标题'",
            'link' => "varchar(255) NULL DEFAULT '' COMMENT '外链'",
            'view' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量'",
            'sort' => "int(10) NOT NULL DEFAULT '0' COMMENT '优先级'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='扩展_博客_友情链接'");

        /* 索引设置 */
        $this->createIndex('article_id','{{%addon_tiny_blog_friendly_link}}','id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_tiny_blog_friendly_link}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

