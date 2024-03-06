<?php

use yii\db\Migration;

class m220227_143428_member extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) NULL DEFAULT '0' COMMENT '商户ID'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'username' => "varchar(20) NOT NULL DEFAULT '' COMMENT '账号'",
            'password_hash' => "varchar(150) NOT NULL DEFAULT '' COMMENT '密码'",
            'auth_key' => "varchar(32) NOT NULL DEFAULT '' COMMENT '授权令牌'",
            'password_reset_token' => "varchar(150) NULL DEFAULT '' COMMENT '密码重置令牌'",
            'mobile_reset_token' => "varchar(150) NULL DEFAULT '' COMMENT '手机号码重置令牌'",
            'type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'realname' => "varchar(50) NULL DEFAULT '' COMMENT '真实姓名'",
            'nickname' => "varchar(60) NULL DEFAULT '' COMMENT '昵称'",
            'head_portrait' => "char(150) NULL DEFAULT '' COMMENT '头像'",
            'gender' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]'",
            'qq' => "varchar(20) NULL DEFAULT '' COMMENT 'qq'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'birthday' => "date NULL COMMENT '生日'",
            'province_id' => "int(10) NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) NULL DEFAULT '0' COMMENT '城市'",
            'area_id' => "int(10) NULL DEFAULT '0' COMMENT '地区'",
            'address' => "varchar(100) NULL DEFAULT '' COMMENT '默认地址'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'tel_no' => "varchar(20) NULL DEFAULT '' COMMENT '电话号码'",
            'bg_image' => "varchar(200) NULL DEFAULT '' COMMENT '个人背景图'",
            'description' => "varchar(200) NULL DEFAULT '' COMMENT '个人说明'",
            'visit_count' => "smallint(5) unsigned NULL DEFAULT '0' COMMENT '访问次数'",
            'last_time' => "int(10) NULL DEFAULT '0' COMMENT '最后一次登录时间'",
            'last_ip' => "varchar(40) NULL DEFAULT '' COMMENT '最后一次登录ip'",
            'role' => "smallint(6) NULL DEFAULT '10' COMMENT '权限'",
            'current_level' => "tinyint(4) NULL DEFAULT '1' COMMENT '当前级别'",
            'level_expiration_time' => "int(10) NULL DEFAULT '0' COMMENT '等级到期时间'",
            'level_buy_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:赠送;2:购买'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'level' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'tree' => "varchar(2000) NULL DEFAULT '' COMMENT '树'",
            'promoter_code' => "varchar(50) NULL DEFAULT '' COMMENT '推广码'",
            'certification_type' => "tinyint(4) NULL DEFAULT '0' COMMENT '认证类型'",
            'source' => "varchar(50) NULL DEFAULT '' COMMENT '注册来源'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员表'");

        // 商户
        if (Yii::$app->services->devPattern->isB2C()) {
            $this->createTable('{{%merchant}}', [
                'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
                'title' => "varchar(200) NULL DEFAULT '' COMMENT '商户名称'",
                'cover' => "char(150) NULL DEFAULT '' COMMENT '店铺头像'",
                'address_name' => "varchar(200) NULL DEFAULT '' COMMENT '地址'",
                'address_details' => "varchar(100) NULL DEFAULT '' COMMENT '详细地址'",
                'longitude' => "varchar(100) NULL DEFAULT '' COMMENT '经度'",
                'latitude' => "varchar(100) NULL DEFAULT '' COMMENT '纬度'",
                'collect_num' => "int(10) unsigned NULL DEFAULT '0' COMMENT '收藏数量'",
                'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
                'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
                'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
                'PRIMARY KEY (`id`)'
            ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商家'");
        }

        /* 索引设置 */


        /* 表数据 */
        $this->insert('{{%member}}',['id'=>'1','merchant_id'=>'0','store_id'=>'0','username'=>'Q2dGP','password_hash'=>'$2y$13$L4waegfC3ABCW97DfiRRbe69WBZDC6kqc0TN3aPq7Rej/5H8RknjK','auth_key'=>'','password_reset_token'=>'','mobile_reset_token'=>'','type'=>'2','realname'=>'简言','nickname'=>'','head_portrait'=>'','gender'=>'0','qq'=>'','email'=>'751393839@qq.com','birthday'=>NULL,'province_id'=>'330000','city_id'=>'330200','area_id'=>NULL,'address'=>'','mobile'=>'','tel_no'=>'','visit_count'=>'0','last_time'=>'1645967024','last_ip'=>'127.0.0.1','role'=>'10','current_level'=>'1','level_expiration_time'=>'0','level_buy_type'=>'1','pid'=>'0','level'=>'1','tree'=>'0-','promoter_code'=>'OL85VX','certification_type'=>'0','status'=>'1','created_at'=>'0','updated_at'=>'1645968229']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member}}');
        if (Yii::$app->services->devPattern->isB2C()) {
            $this->dropTable('{{%merchant}}');
        }
        $this->execute('SET foreign_key_checks = 1;');
    }
}

