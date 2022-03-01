<?php

use yii\db\Migration;

class m220227_143427_common_archives extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_archives}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(11) NULL DEFAULT '0' COMMENT '申请人'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '用户类型'",
            'certification_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '认证类型[1:公司;2:个人]'",
            'profit_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '盈利类型[1:私立;2:国有]'",
            'company_name' => "varchar(255) NULL DEFAULT '' COMMENT '公司名称'",
            'unified_social_credit_code' => "varchar(200) NULL DEFAULT '' COMMENT '统一社会信用代码'",
            'business_license' => "varchar(255) NULL DEFAULT '' COMMENT '营业执照'",
            'business_scope' => "varchar(3000) NULL COMMENT '经营范围'",
            'practice_qualification_certificate' => "varchar(255) NULL DEFAULT '' COMMENT '执业资格证'",
            'establish_year' => "date NULL COMMENT '成立年份'",
            'floor_space' => "double(10,2) NULL DEFAULT '0' COMMENT '占地面积'",
            'content' => "text NULL COMMENT '详情'",
            'corporate_realname' => "varchar(100) NULL DEFAULT '' COMMENT '法人真实姓名'",
            'corporate_mobile' => "varchar(50) NULL DEFAULT '' COMMENT '法人手机号码'",
            'corporate_identity_card' => "varchar(30) NULL DEFAULT '' COMMENT '法人身份证'",
            'corporate_identity_card_front' => "varchar(255) NULL DEFAULT '' COMMENT '法人身份证正面(国徽)'",
            'corporate_identity_card_back' => "varchar(255) NULL DEFAULT '' COMMENT '法人身份证反面(人面)'",
            'bank_account_name' => "varchar(100) NULL DEFAULT '' COMMENT '公司银行开户名'",
            'bank_account_number' => "varchar(100) NULL DEFAULT '' COMMENT '公司银行账号'",
            'bank_branch_name' => "varchar(100) NULL DEFAULT '' COMMENT '开户银行支行名称'",
            'bank_location' => "varchar(100) NULL DEFAULT '' COMMENT '开户银行所在地'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公用_认证信息'");
        
        /* 索引设置 */
        $this->createIndex('merchant_id','{{%common_archives}}','merchant_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_archives}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

