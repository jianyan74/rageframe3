<?php

use yii\db\Migration;

class m220227_143428_member_account extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%member_account}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'store_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺ID'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'member_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1:会员;2:后台管理员;3:商家管理员'",
            'user_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '当前余额'",
            'accumulate_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计余额'",
            'give_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计赠送余额'",
            'consume_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计消费金额'",
            'frozen_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '冻结金额'",
            'user_integral' => "int(11) NULL DEFAULT '0' COMMENT '当前积分'",
            'accumulate_integral' => "int(11) NULL DEFAULT '0' COMMENT '累计积分'",
            'give_integral' => "int(11) NULL DEFAULT '0' COMMENT '累计赠送积分'",
            'consume_integral' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计消费积分'",
            'frozen_integral' => "int(11) NULL DEFAULT '0' COMMENT '冻结积分'",
            'user_growth' => "int(11) NULL DEFAULT '0' COMMENT '当前成长值'",
            'accumulate_growth' => "int(11) NULL DEFAULT '0' COMMENT '累计成长值'",
            'consume_growth' => "int(10) NULL DEFAULT '0' COMMENT '累计消费成长值'",
            'frozen_growth' => "int(10) NULL DEFAULT '0' COMMENT '冻结成长值'",
            'economize_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '已节约金额'",
            'accumulate_drawn_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计提现'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='会员_账户表'");

        /* 索引设置 */
        $this->createIndex('member_id','{{%member_account}}','member_id',0);
        $this->createIndex('merchant_id','{{%member_account}}','merchant_id, member_type',0);


        /* 表数据 */
        $this->insert('{{%member_account}}',['id'=>'1','merchant_id'=>'0','store_id'=>'0','member_id'=>'1','member_type'=>'2','user_money'=>'0.00','accumulate_money'=>'0.00','give_money'=>'0.00','consume_money'=>'0.00','frozen_money'=>'0.00','user_integral'=>'0','accumulate_integral'=>'0','give_integral'=>'0','consume_integral'=>'0.00','frozen_integral'=>'0','user_growth'=>'0','accumulate_growth'=>'0','consume_growth'=>'0','frozen_growth'=>'0','economize_money'=>'0.00','accumulate_drawn_money'=>'0.00','status'=>'1']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_account}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

