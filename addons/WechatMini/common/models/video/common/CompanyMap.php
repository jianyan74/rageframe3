<?php

namespace addons\WechatMini\common\models\video\common;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_account_company_map}}".
 *
 * @property int $merchant_id
 * @property int $account_company_id
 * @property int $express_company_id
 */
class CompanyMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_company_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'express_company_id'], 'integer'],
            [['account_company_id'], 'string'],
            [['account_company_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => 'Merchant ID',
            'account_company_id' => 'Account Company ID',
            'express_company_id' => 'Map ID',
        ];
    }
}
