<?php

use yii\db\Migration;

class m220227_143428_member_bank_account extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_bank_account}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) NULL DEFAULT '0' COMMENT '会员id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'realname' => "varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名'",
            'mobile' => "varchar(20) NOT NULL DEFAULT '' COMMENT '手机号'",
            'account_number' => "varchar(50) NULL DEFAULT '' COMMENT '银行账号'",
            'account_type' => "int(11) NULL DEFAULT '10' COMMENT '账户类型'",
            'account_type_name' => "varchar(30) NULL DEFAULT '' COMMENT '账户类型名称'",
            'bank_name' => "varchar(100) NULL DEFAULT '' COMMENT '银行信息'",
            'bank_branch' => "varchar(200) NULL DEFAULT '' COMMENT '银行支行信息'",
            'identity_card' => "varchar(20) NULL DEFAULT '' COMMENT '身份证'",
            'identity_card_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证正面'",
            'identity_card_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证背面'",
            'is_default' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否默认账号'",
            'audit_status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '审核状态[0:申请;1通过;-1失败]'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员_提现账号'");
        
        /* 索引设置 */
        $this->createIndex('IDX_member_bank_account_uid','{{%member_bank_account}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_bank_account}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

