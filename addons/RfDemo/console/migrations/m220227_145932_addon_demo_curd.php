<?php

use yii\db\Migration;

class m220227_145932_addon_demo_curd extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_demo_curd}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'cate_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '分类'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '排序'",
            'gender' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别1男2女'",
            'content' => "text NOT NULL COMMENT '内容'",
            'tag' => "varchar(100) NOT NULL DEFAULT '' COMMENT '标签'",
            'cover' => "varchar(100) NOT NULL DEFAULT '' COMMENT '图片'",
            'covers' => "json NOT NULL COMMENT '图片组'",
            'file' => "varchar(100) NOT NULL DEFAULT '' COMMENT '文件'",
            'files' => "json NOT NULL COMMENT '文件组'",
            'keywords' => "varchar(100) NOT NULL DEFAULT '' COMMENT '关键字'",
            'description' => "varchar(200) NOT NULL DEFAULT '' COMMENT '描述'",
            'price' => "decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格'",
            'views' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击'",
            'start_time' => "int(10) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(10) NULL DEFAULT '0' COMMENT '结束时间'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'province_id' => "int(10) NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) NULL DEFAULT '0' COMMENT '市'",
            'area_id' => "int(10) NULL DEFAULT '0' COMMENT '区'",
            'ip' => "varchar(50) NULL DEFAULT '' COMMENT 'ip'",
            'date' => "date NULL COMMENT '日期'",
            'time' => "varchar(20) NULL DEFAULT '' COMMENT '时间'",
            'color' => "varchar(7) NULL DEFAULT '' COMMENT '颜色'",
            'head_portrait' => "varchar(200) NULL DEFAULT '' COMMENT '头像'",
            'longitude' => "varchar(30) NULL COMMENT '经纬度'",
            'latitude' => "varchar(30) NULL COMMENT '经纬度'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'multiple_input' => "json NULL COMMENT '多输入框'",
            'cate_ids' => "json NULL COMMENT '分类组'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_示例插件_curd'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_demo_curd}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

