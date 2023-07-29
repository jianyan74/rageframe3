<?php

use yii\db\Migration;

class m230729_053756_merchant extends Migration
{
    public function up()
    {
        if (!Yii::$app->services->devPattern->isB2C()) {
            return false;
        }

        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%merchant}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '商户名称'",
            'cover' => "char(150) NULL DEFAULT '' COMMENT '店铺头像'",
            'address_name' => "varchar(200) NULL DEFAULT '' COMMENT '地址'",
            'address_details' => "varchar(100) NULL DEFAULT '' COMMENT '详细地址'",
            'collect_num' => "int(10) unsigned NULL DEFAULT '0' COMMENT '收藏数量'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商家'");

        /* 索引设置 */


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        if (!Yii::$app->services->devPattern->isB2C()) {
            return false;
        }

        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

