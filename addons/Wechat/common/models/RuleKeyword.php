<?php

namespace addons\Wechat\common\models;

use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%addon_wechat_rule_keyword}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $rule_id 规则ID
 * @property string $module 模块名
 * @property string $content 内容
 * @property int $type 类别
 * @property int $sort 优先级
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 */
class RuleKeyword extends \yii\db\ActiveRecord
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_rule_keyword}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'rule_id', 'type', 'sort', 'status'], 'integer'],
            [['module'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'rule_id' => '规则ID',
            'module' => '模块名',
            'content' => '内容',
            'type' => '类别',
            'sort' => '优先级',
            'status' => '状态[-1:删除;0:禁用;1启用]',
        ];
    }
}
