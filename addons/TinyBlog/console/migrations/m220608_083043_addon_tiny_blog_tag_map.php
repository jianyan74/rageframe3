<?php

use yii\db\Migration;

class m220608_083043_addon_tiny_blog_tag_map extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_tiny_blog_tag_map}}', [
            'tag_id' => "int(10) NULL DEFAULT '0' COMMENT '标签id'",
            'article_id' => "int(10) NULL DEFAULT '0' COMMENT '文章id'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='扩展_博客_文章标签关联'");

        /* 索引设置 */
        $this->createIndex('tag_id','{{%addon_tiny_blog_tag_map}}','tag_id',0);
        $this->createIndex('article_id','{{%addon_tiny_blog_tag_map}}','article_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_tiny_blog_tag_map}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

