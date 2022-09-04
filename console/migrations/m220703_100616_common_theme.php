<?php

use yii\db\Migration;

class m220703_100616_common_theme extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%common_theme}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(10) NULL DEFAULT '0' COMMENT '用户ID'",
            'member_type' => "int(10) NULL DEFAULT '0' COMMENT '用户类型'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'layout' => "varchar(50) NULL COMMENT '布局类型'",
            'color' => "varchar(50) NULL DEFAULT 'black' COMMENT '主题颜色'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_用户主题'");

        /* 索引设置 */
        $this->createIndex('member_id','{{%common_theme}}','member_id',0);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_theme}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

