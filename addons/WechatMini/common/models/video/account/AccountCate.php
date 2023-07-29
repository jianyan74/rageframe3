<?php

namespace addons\WechatMini\common\models\video\account;

use Yii;

/**
 * This is the model class for table "{{%addon_wechat_mini_video_account_cate}}".
 *
 * @property int $id
 * @property int $merchant_id
 * @property int|null $store_id 店铺ID
 * @property int $first_cat_id 一级类目
 * @property string|null $first_cat_name 一级类目名称
 * @property int $second_cat_id 二级类目
 * @property string|null $second_cat_name 二级类目名称
 * @property int|null $third_cat_id 三级类目
 * @property string|null $third_cat_name 三级类目名称
 */
class AccountCate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_account_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'first_cat_id', 'second_cat_id', 'third_cat_id'], 'integer'],
            [['first_cat_name', 'second_cat_name', 'third_cat_name'], 'string', 'max' => 100],
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
            'first_cat_id' => '一级类目',
            'first_cat_name' => '一级类目名称',
            'second_cat_id' => '二级类目',
            'second_cat_name' => '二级类目名称',
            'third_cat_id' => '三级类目',
            'third_cat_name' => '三级类目名称',
        ];
    }
}
