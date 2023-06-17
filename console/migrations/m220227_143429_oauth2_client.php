<?php

use yii\db\Migration;

class m220227_143429_oauth2_client extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%oauth2_client}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'title' => "varchar(100) NOT NULL DEFAULT '' COMMENT '标题'",
            'client_id' => "varchar(64) NOT NULL COMMENT '授权ID'",
            'client_secret' => "varchar(64) NOT NULL COMMENT '授权秘钥'",
            'redirect_uri' => "varchar(2000) NULL DEFAULT '' COMMENT '回调Url'",
            'remark' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'group' => "varchar(30) NULL DEFAULT '' COMMENT '组别'",
            'scope' => "json NULL COMMENT '授权'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='oauth2_授权客户端'");

        /* 索引设置 */
        $this->createIndex('client_id','{{%oauth2_client}}','client_id',0);


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%oauth2_client}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

