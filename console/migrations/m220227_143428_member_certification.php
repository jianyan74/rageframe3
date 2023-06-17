<?php

use yii\db\Migration;

class m220227_143428_member_certification extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_certification}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '用户类型'",
            'realname' => "varchar(100) NULL DEFAULT '' COMMENT '真实姓名'",
            'identity_card' => "varchar(50) NULL DEFAULT '' COMMENT '身份证号码'",
            'identity_card_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证国徽面'",
            'identity_card_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证人像面'",
            'gender' => "varchar(10) NULL DEFAULT '' COMMENT '性别'",
            'birthday' => "date NULL COMMENT '生日'",
            'front_is_fake' => "tinyint(4) NULL DEFAULT '0' COMMENT '正面是否是复印件'",
            'back_is_fake' => "tinyint(4) NULL DEFAULT '0' COMMENT '背面是否是复印件'",
            'nationality' => "varchar(100) NULL DEFAULT '' COMMENT '民族 '",
            'address' => "varchar(255) NULL DEFAULT '' COMMENT '地址'",
            'start_date' => "date NULL COMMENT '有效期起始时间'",
            'end_date' => "date NULL COMMENT '有效期结束时间'",
            'issue' => "varchar(200) NULL DEFAULT '' COMMENT '签发机关 '",
            'is_self' => "tinyint(4) NULL DEFAULT '0' COMMENT '自己认证'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员_实名认证'");

        /* 索引设置 */
        $this->createIndex('member_id','{{%member_certification}}','member_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_certification}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

