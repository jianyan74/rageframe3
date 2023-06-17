<?php

use yii\db\Migration;

class m230617_045324_member_stat extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_stat}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺id'",
            'member_id' => "int(10) NULL COMMENT '用户id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'nice_num' => "int(10) NULL DEFAULT '0' COMMENT '点赞数量'",
            'disagree_num' => "int(10) NULL DEFAULT '0' COMMENT '不赞同数量'",
            'transmit_num' => "int(11) NULL DEFAULT '0' COMMENT '转发数量'",
            'comment_num' => "int(10) NULL DEFAULT '0' COMMENT '评论数量'",
            'collect_num' => "int(10) NULL DEFAULT '0' COMMENT '收藏'",
            'report_num' => "int(10) NULL DEFAULT '0' COMMENT '举报数量'",
            'recommend_num' => "int(10) NULL DEFAULT '0' COMMENT '推荐数量'",
            'follow_num' => "int(10) NULL DEFAULT '0' COMMENT '关注人数'",
            'allowed_num' => "int(10) NULL DEFAULT '0' COMMENT '被关注人数'",
            'view' => "int(10) NULL DEFAULT '0' COMMENT '浏览量'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='食谱_作品'");

        /* 索引设置 */
        $this->createIndex('member_id','{{%member_stat}}','member_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_stat}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

