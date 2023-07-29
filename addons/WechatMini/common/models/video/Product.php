<?php

namespace addons\WechatMini\common\models\video;

use addons\WechatMini\common\models\video\spu\Spu;

/**
 * Class Product
 * @package addons\WechatMini\common\models\video
 * @author jianyan74 <751393839@qq.com>
 */
class Product extends \addons\TinyShop\common\models\product\Product
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpu()
    {
        return $this->hasOne(Spu::class, ['out_product_id' => 'id']);
    }
}
