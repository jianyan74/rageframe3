<?php

use yii\db\Migration;

class m220227_143428_extend_pay_refund extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%extend_pay_refund}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id'",
            'pay_id' => "int(11) NULL DEFAULT '0' COMMENT '支付ID'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) NULL DEFAULT '0' COMMENT '买家id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'order_sn' => "varchar(30) NULL DEFAULT '' COMMENT '关联订单号'",
            'order_group' => "varchar(20) NULL DEFAULT '' COMMENT '组别[默认统一支付类型]'",
            'out_trade_no' => "varchar(32) NULL DEFAULT '' COMMENT '商户订单号'",
            'transaction_id' => "varchar(50) NULL DEFAULT '' COMMENT '微信订单号'",
            'refund_trade_no' => "varchar(55) NULL DEFAULT '' COMMENT '退款交易号'",
            'refund_money' => "decimal(10,2) NULL COMMENT '退款金额'",
            'refund_way' => "int(11) NULL DEFAULT '0' COMMENT '退款方式'",
            'ip' => "varchar(30) NULL DEFAULT '' COMMENT '申请者ip'",
            'error_data' => "json NULL COMMENT '报错日志'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'remark' => "varchar(255) NULL DEFAULT '' COMMENT '备注'",
            'req_id' => "varchar(50) NULL DEFAULT '' COMMENT '对外id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0'",
            'updated_at' => "int(10) NULL DEFAULT '0'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='扩展_支付退款记录'");

        /* 索引设置 */
        $this->createIndex('order_sn','{{%extend_pay_refund}}','order_sn',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%extend_pay_refund}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

