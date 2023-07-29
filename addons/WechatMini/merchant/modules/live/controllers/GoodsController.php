<?php

namespace addons\WechatMini\merchant\modules\live\controllers;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\traits\MerchantCurd;
use addons\WechatMini\merchant\controllers\BaseController;
use addons\WechatMini\common\models\live\Goods;
use addons\WechatMini\common\models\live\GoodsMap;
use addons\WechatMini\common\models\live\Live;

/**
 * 商品管理
 *
 * Class GoodsController
 * @package addons\WechatMini\merchant\modules\live\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GoodsController extends BaseController
{
    use MerchantCurd;

    /**
     * @var string
     */
    public $modelClass = '';

    /**
     * 同步
     *
     * @param int $offset
     * @param int $count
     * @param int $clear
     * @return array|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSync($offset = 0, $count = 20, $clear = 0)
    {
        if ($clear == StatusEnum::ENABLED) {
            Goods::updateAll(['status' => StatusEnum::DELETE], ['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
        }

        try {
            $res = Yii::$app->wechatMiniService->liveGoods->sync($offset, $count);
            if (is_array($res)) {
                return ResultHelper::json(200, '同步成功', $res);
            }

            return ResultHelper::json(201, '同步完成');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }
}
