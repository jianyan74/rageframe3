<?php

namespace addons\WechatMini\services\live;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use addons\WechatMini\common\models\live\Goods;

/**
 * Class GoodsService
 * @package addons\WechatMini\services\live
 * @author jianyan74 <751393839@qq.com>
 */
class GoodsService extends Service
{
    public function syncByRoom($goods)
    {

    }

    /**
     * @param $offset
     * @param $count
     * @param $auditStatus
     * @return true|void
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sync($offset, $count, $auditStatus = 1)
    {
        $lists = Yii::$app->wechat->miniProgram->broadcast->getApproved([
            'offset' => $offset,
            'limit' => $count,
            'status' => $auditStatus, // 商品状态，0：未审核。1：审核中，2：审核通过，3：审核驳回
        ]);

        if (empty($lists['goods'])) {
            return true;
        }

        $rows = [];
        foreach ($lists['goods'] as $goods) {
            // 插入产品
            $rows[] = [
                'merchant_id' => Yii::$app->services->merchant->getNotNullId(),
                'store_id' => Yii::$app->services->store->getNotNullId(),
                'cover_img' => $goods['coverImgUrl'],
                'goods_id' => $goods['goodsId'],
                'name' => $goods['name'],
                'url' => $goods['url'],
                'price' => $goods['price'],
                'price_two' => $goods['price2'],
                'price_type' => $goods['priceType'],
                'third_party_tag' => $goods['thirdPartyTag'],
                'third_party_appid' => $goods['thirdPartyAppid'],
                'audit_status' => $auditStatus,
                'status' => StatusEnum::ENABLED,
                'created_at' => time(),
                'updated_at' => time()
            ];
        }

        if (!empty($rows)) {
            $field = array_keys($rows[0]);
            !empty($rows) && Yii::$app->db->createCommand()->batchInsert(Goods::tableName(), $field, $rows)->execute();
        }
    }
}
