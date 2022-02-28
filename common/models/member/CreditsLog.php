<?php

namespace common\models\member;

use Yii;
use common\enums\StatusEnum;
use common\traits\HasOneMember;
use common\traits\HasOneMerchant;

/**
 * This is the model class for table "{{%member_credits_log}}".
 *
 * @property int $id
 * @property string|null $app_id 应用id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property int|null $member_type 1:会员;2:后台管理员;3:商家管理员
 * @property int|null $pay_type 支付类型
 * @property string $type 变动类型[integral:积分;money:余额]
 * @property string|null $group 变动的组别
 * @property float|null $old_num 之前的数据
 * @property float|null $new_num 变动后的数据
 * @property float|null $num 变动的数据
 * @property string|null $remark 备注
 * @property string|null $ip ip地址
 * @property int|null $map_id 关联id
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class CreditsLog extends \common\models\base\BaseModel
{
    use HasOneMember, HasOneMerchant;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_credits_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'member_type', 'pay_type', 'map_id', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['old_num', 'new_num', 'num'], 'number'],
            [['app_id', 'type', 'group', 'ip'], 'string', 'max' => 50],
            [['remark', 'addon_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '应用id',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'member_type' => '1:会员;2:后台管理员;3:商家管理员',
            'pay_type' => '支付类型',
            'type' => '变动类型[integral:积分;money:余额]',
            'group' => '变动的组别',
            'old_num' => '之前的数据',
            'new_num' => '变动后的数据',
            'num' => '变动的数据',
            'remark' => '备注',
            'ip' => 'ip地址',
            'map_id' => '关联id',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->app_id = Yii::$app->id;
            $this->ip = Yii::$app->services->base->getUserIp();
            $this->addon_name = Yii::$app->params['addon']['name'] ?? '';
            !empty($this->addon_name) && $this->is_addon = StatusEnum::ENABLED;
        }

        return parent::beforeSave($insert);
    }
}
