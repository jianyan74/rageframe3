<?php

namespace addons\Wechat;

use Yii;
use common\components\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\Wechat
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
                $this->alterColumn('{{%addon_wechat_attachment_news}}', 'thumb_url', $this->string(500));
                $this->alterColumn('{{%addon_wechat_attachment_news}}', 'media_url', $this->string(500));
                break;
        }
    }
}
