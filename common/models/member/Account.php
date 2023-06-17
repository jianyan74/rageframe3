<?php

namespace common\models\member;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%member_account}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $member_id 用户id
 * @property int|null $member_type 用户类型
 * @property float|null $user_money 当前余额
 * @property float|null $accumulate_money 累计余额
 * @property float|null $give_money 累计赠送余额
 * @property float|null $consume_money 累计消费金额
 * @property float|null $frozen_money 冻结金额
 * @property int|null $user_integral 当前积分
 * @property int|null $accumulate_integral 累计积分
 * @property int|null $give_integral 累计赠送积分
 * @property float|null $consume_integral 累计消费积分
 * @property int|null $frozen_integral 冻结积分
 * @property int|null $user_growth 当前成长值
 * @property int|null $accumulate_growth 累计成长值
 * @property int|null $consume_growth 累计消费成长值
 * @property int|null $frozen_growth 冻结成长值
 * @property float|null $economize_money 已节约金额
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 */
class Account extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'merchant_id',
                    'store_id',
                    'member_id',
                    'member_type',
                    'user_integral',
                    'accumulate_integral',
                    'give_integral',
                    'frozen_integral',
                    'user_growth',
                    'accumulate_growth',
                    'consume_growth',
                    'frozen_growth',
                    'status',
                ],
                'integer',
            ],
            [
                [
                    'user_money',
                    'accumulate_money',
                    'give_money',
                    'consume_money',
                    'frozen_money',
                    'consume_integral',
                    'economize_money',
                ],
                'number',
            ],
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
            'member_id' => '用户id',
            'member_type' => '用户类型',
            'user_money' => '当前余额',
            'accumulate_money' => '累计余额',
            'give_money' => '累计赠送余额',
            'consume_money' => '累计消费金额',
            'frozen_money' => '冻结金额',
            'user_integral' => '当前积分',
            'accumulate_integral' => '累计积分',
            'give_integral' => '累计赠送积分',
            'consume_integral' => '累计消费积分',
            'frozen_integral' => '冻结积分',
            'user_growth' => '当前成长值',
            'accumulate_growth' => '累计成长值',
            'consume_growth' => '累计消费成长值',
            'frozen_growth' => '冻结成长值',
            'economize_money' => '已节约金额',
            'status' => '状态',
        ];
    }
}
