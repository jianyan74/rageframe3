<?php

use yii\db\Migration;

class m220227_143429_member_withdraw_deposit extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_withdraw_deposit}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) NULL COMMENT '会员id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'withdraw_no' => "varchar(100) NULL DEFAULT '' COMMENT '提现流水号'",
            'batch_no' => "varchar(100) NULL DEFAULT '' COMMENT '批量转账单号'",
            'realname' => "varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名'",
            'mobile' => "varchar(20) NOT NULL DEFAULT '' COMMENT '手机号'",
            'account_number' => "varchar(50) NULL DEFAULT '' COMMENT '银行账号'",
            'account_type' => "int(11) NULL DEFAULT '1' COMMENT '账户类型'",
            'account_type_name' => "varchar(30) NULL DEFAULT '' COMMENT '账户类型名称'",
            'bank_name' => "varchar(100) NULL DEFAULT '' COMMENT '银行信息'",
            'bank_branch' => "varchar(200) NULL DEFAULT '' COMMENT '银行支行信息'",
            'identity_card' => "varchar(20) NULL DEFAULT '' COMMENT '身份证'",
            'identity_card_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证正面'",
            'identity_card_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证背面'",
            'cash' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '提现金额'",
            'memo' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'transfer_type' => "int(11) NULL DEFAULT '1' COMMENT '转账方式'",
            'transfer_name' => "varchar(50) NULL DEFAULT '' COMMENT '转账银行名称'",
            'transfer_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '转账金额'",
            'transfer_remark' => "varchar(200) NULL DEFAULT '' COMMENT '转账备注'",
            'transfer_no' => "varchar(100) NULL DEFAULT '' COMMENT '转账流水号'",
            'transfer_account_no' => "varchar(100) NULL DEFAULT '' COMMENT '转账银行账号'",
            'transfer_result' => "varchar(200) NULL DEFAULT '' COMMENT '转账结果'",
            'transfer_time' => "int(11) NULL DEFAULT '0' COMMENT '转账时间'",
            'transfer_status' => "int(11) NULL DEFAULT '0' COMMENT '转账状态'",
            'payment_time' => "int(11) NULL DEFAULT '0' COMMENT '到账时间'",
            'audit_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '审核时间'",
            'service_charge' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '手续费率金额'",
            'service_charge_rate' => "decimal(10,2) NULL COMMENT '手续费率'",
            'service_charge_single' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '手续费单笔'",
            'service_charge_total' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '总手续费'",
            'refusal_cause' => "varchar(200) NULL DEFAULT '' COMMENT '拒绝原因'",
            'notify_url' => "varchar(255) NULL DEFAULT '' COMMENT '通知地址'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员_提现记录表'");

        /* 索引设置 */
        $this->createIndex('withdraw_no','{{%member_withdraw_deposit}}','withdraw_no',0);
        $this->createIndex('merchant_id','{{%member_withdraw_deposit}}','merchant_id, member_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_withdraw_deposit}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

