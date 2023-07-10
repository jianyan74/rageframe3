<?php

use yii\db\Migration;

class m220608_083043_addon_tiny_blog_tag extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_tiny_blog_tag}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺id'",
            'title' => "varchar(30) NOT NULL DEFAULT '' COMMENT '标题'",
            'frequency' => "int(10) NULL DEFAULT '0' COMMENT '使用次数'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '排序'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='扩展_博客_文章标签'");

        /* 索引设置 */
        $this->createIndex('merchant_id','{{%addon_tiny_blog_tag}}','merchant_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_tiny_blog_tag}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

