<?php

use yii\db\Migration;

class m220227_143429_member_tag_map extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_tag_map}}', [
            'tag_id' => "int(10) NULL DEFAULT '0' COMMENT '标签id'",
            'member_id' => "int(10) NULL DEFAULT '0' COMMENT '文章id'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_标签关联表'");
        
        /* 索引设置 */
        $this->createIndex('tag_id','{{%member_tag_map}}','tag_id',0);
        $this->createIndex('article_id','{{%member_tag_map}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_tag_map}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

