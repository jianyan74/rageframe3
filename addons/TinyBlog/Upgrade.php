<?php

namespace addons\TinyBlog;

use Yii;
use common\components\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\TinyBlog
 */
class Upgrade extends Migration implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '1.0.0', // 默认版本
        '1.0.1',
    ];

    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        switch ($addon->version) {
            case '1.0.1' :
                $this->addColumn('{{%addon_tiny_blog_tag}}', 'frequency', "int(10) NULL DEFAULT '0' COMMENT '使用次数'");
                break;
        }
    }
}
