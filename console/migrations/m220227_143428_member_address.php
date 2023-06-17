<?php

use yii\db\Migration;

class m220227_143428_member_address extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_address}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'realname' => "varchar(100) NULL DEFAULT '' COMMENT '真实姓名'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'province_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '市'",
            'area_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '区'",
            'name' => "varchar(200) NULL DEFAULT '' COMMENT '省市区名称'",
            'details' => "varchar(200) NULL DEFAULT '' COMMENT '详细地址'",
            'street_number' => "varchar(200) NULL DEFAULT '' COMMENT '门牌号'",
            'longitude' => "varchar(100) NULL DEFAULT '' COMMENT '经度'",
            'latitude' => "varchar(100) NULL DEFAULT '' COMMENT '纬度'",
            'floor_level' => "tinyint(5) NULL DEFAULT '0' COMMENT '楼层'",
            'zip_code' => "varchar(10) NULL DEFAULT '' COMMENT '邮编'",
            'tel_no' => "varchar(20) NULL DEFAULT '' COMMENT '家庭号码'",
            'is_default' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '默认地址'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员_收货地址表'");
        
        /* 索引设置 */
        $this->createIndex('member_id','{{%member_address}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_address}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

