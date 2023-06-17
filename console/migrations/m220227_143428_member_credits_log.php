<?php

use yii\db\Migration;

class m220227_143428_member_credits_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_credits_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'pay_type' => "tinyint(4) NULL DEFAULT '0' COMMENT '支付类型'",
            'type' => "varchar(50) NOT NULL DEFAULT '' COMMENT '变动类型[integral:积分;money:余额]'",
            'group' => "varchar(50) NULL DEFAULT '' COMMENT '变动的组别'",
            'old_num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '之前的数据'",
            'new_num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '变动后的数据'",
            'num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '变动的数据'",
            'remark' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'ip' => "varchar(50) NULL DEFAULT '' COMMENT 'ip地址'",
            'map_id' => "int(10) NULL DEFAULT '0' COMMENT '关联id'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员_积分等变动表'");

        /* 索引设置 */
        $this->createIndex('member_id','{{%member_credits_log}}','member_id',0);
        $this->createIndex('member_type','{{%member_credits_log}}','member_type, status',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_credits_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

