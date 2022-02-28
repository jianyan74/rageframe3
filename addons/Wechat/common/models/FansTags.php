<?php

namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%addon_wechat_fans_tags}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户ID
 * @property int|null $shop_id 店铺ID
 * @property string|null $tags 标签
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class FansTags extends BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_fans_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shop_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tags'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'shop_id' => '店铺ID',
            'tags' => '标签',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
