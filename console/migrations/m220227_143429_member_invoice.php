<?php

use yii\db\Migration;

class m220227_143429_member_invoice extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_invoice}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '公司抬头'",
            'duty_paragraph' => "varchar(200) NULL DEFAULT '' COMMENT '公司税号'",
            'opening_bank' => "varchar(255) NULL DEFAULT '' COMMENT '公司开户行'",
            'opening_bank_account' => "varchar(100) NULL DEFAULT '' COMMENT '公司开户行账号'",
            'address' => "varchar(255) NULL DEFAULT '' COMMENT '公司地址'",
            'phone' => "varchar(50) NULL DEFAULT '' COMMENT '公司电话'",
            'remark' => "varchar(255) NULL DEFAULT '' COMMENT '备注'",
            'is_default' => "tinyint(2) unsigned NULL DEFAULT '0' COMMENT '默认'",
            'type' => "tinyint(4) NULL DEFAULT '1' COMMENT '类型 1企业 2个人'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员_发票'");

        /* 索引设置 */


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_invoice}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

