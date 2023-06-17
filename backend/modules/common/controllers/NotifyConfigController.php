<?php

namespace backend\modules\common\controllers;

use Yii;
use common\enums\NotifyConfigTypeEnum;
use common\traits\NotifyConfigTrait;
use common\models\common\NotifyConfig;
use backend\controllers\BaseController;
use common\enums\SubscriptionActionEnum;

/**
 * Class NotifyConfigController
 * @package addons\TinyShop\backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyConfigController extends BaseController
{
    use NotifyConfigTrait;

    /**
     * @var NotifyConfig
     */
    public $modelClass = NotifyConfig::class;

    /**
     * @var string
     */
    public $viewPrefix = '@backend/modules/common/views/notify-config/';

    /**
     * @return array|string[]
     */
    public function getNameMap()
    {
        return SubscriptionActionEnum::getMap();
    }

    /**
     * 默认值
     *
     * @return array|string[]
     */
    public function getNameDefaultData($name)
    {
        return SubscriptionActionEnum::default($name);
    }

    /**
     * @return array|string[]
     */
    public function getTypeMap()
    {
        $map = NotifyConfigTypeEnum::getMap();
        unset(
            $map[NotifyConfigTypeEnum::SMS],
            $map[NotifyConfigTypeEnum::DING_TALK],
            $map[NotifyConfigTypeEnum::EMAIL],
            $map[NotifyConfigTypeEnum::APP_PUSH],
            $map[NotifyConfigTypeEnum::WECHAT_MINI],
        );

        return $map;
    }
}
