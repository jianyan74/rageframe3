<?php

namespace addons\Authority;

use Yii;
use yii\db\Exception;
use common\enums\AppEnum;
use common\components\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\Authority
 */
class Upgrade extends Migration implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '3.0.0', // 默认版本
        '3.0.3', '3.0.10', '3.0.12', '3.0.18', '3.0.25',
        '3.0.28',
    ];

    /**
     * @param $addon
     * @return mixed|void
     * @throws Exception
     */
    public function run($addon)
    {
        switch ($addon->version) {
            case '3.0.25' :
                /* 创建表 */
                $this->createTable('{{%common_theme}}', [
                    'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
                    'merchant_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '商户ID'",
                    'member_id' => "int(10) NULL DEFAULT '0' COMMENT '用户ID'",
                    'member_type' => "int(10) NULL DEFAULT '0' COMMENT '用户类型'",
                    'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
                    'layout' => "varchar(50) NULL COMMENT '布局类型'",
                    'color' => "varchar(50) NULL DEFAULT 'black' COMMENT '主题颜色'",
                    'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
                    'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
                    'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
                    'PRIMARY KEY (`id`)'
                ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_用户主题'");

                /* 索引设置 */
                $this->createIndex('member_id','{{%common_theme}}','member_id',0);
                break;
            case '3.0.18' :
                Yii::$app->services->config->findSaveByName('map_amap_code', AppEnum::BACKEND, [
                    'title' => 'Web端(Js Api)安全秘钥',
                    'name' => 'map_amap_code',
                    'app_id' => 'backend',
                    'type' => 'text',
                    'cate_id' => '32',
                    'extra' => '',
                    'remark' => '地图选择',
                    'is_hide_remark' => '0',
                    'default_value' => '',
                    'sort' => '1',
                    'status' => '1',
                ]);
                break;
            case '3.0.0' :
                break;
        }
    }
}
