<?php

namespace addons\WechatMini\common\models\video\account;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_mini_video_account_brand}}".
 *
 * @property int $id
 * @property int|null $merchant_id
 * @property int|null $store_id 店铺ID
 * @property int|null $brand_id 品牌ID
 * @property string|null $brand_wording 品牌名称
 * @property int|null $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AccountBrand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_account_brand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'brand_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['brand_wording'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'store_id' => '店铺ID',
            'brand_id' => '品牌ID',
            'brand_wording' => '品牌名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
