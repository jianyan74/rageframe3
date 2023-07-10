<?php

use yii\db\Migration;

class m230625_010846_member_cancel extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_cancel}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '会员id'",
            'content' => "text NULL COMMENT '申请内容'",
            'audit_status' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态[0:申请;1通过;-1失败]'",
            'audit_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '审核时间'",
            'refusal_cause' => "varchar(200) NULL DEFAULT '' COMMENT '拒绝原因'",
            'is_addon' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_name' => "varchar(200) NULL DEFAULT '' COMMENT '插件名称'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_注销申请'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_cancel}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

