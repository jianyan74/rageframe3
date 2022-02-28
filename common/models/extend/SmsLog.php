<?php

namespace common\models\extend;

use Yii;

/**
 * This is the model class for table "{{%extend_sms_log}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property string|null $mobile 手机号码
 * @property string|null $code 验证码
 * @property string|null $content 内容
 * @property int|null $error_code 报错code
 * @property string|null $error_msg 报错信息
 * @property string|null $error_data 报错日志
 * @property string|null $usage 用途
 * @property int|null $used 是否使用[0:未使用;1:已使用]
 * @property int|null $use_time 使用时间
 * @property string|null $ip ip地址
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property string|null $req_id 对外id
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class SmsLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%extend_sms_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'error_code', 'used', 'use_time', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['error_data'], 'string'],
            [['mobile', 'usage'], 'string', 'max' => 20],
            [['code'], 'safe'],
            [['content'], 'string', 'max' => 500],
            [['error_msg', 'addon_name'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 30],
            [['req_id'], 'string', 'max' => 50],
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
            'member_id' => '用户id',
            'mobile' => '手机号码',
            'code' => '验证码',
            'content' => '内容',
            'error_code' => 'Code',
            'error_msg' => '报错信息',
            'error_data' => '报错日志',
            'usage' => '用途',
            'used' => '使用情况',
            'use_time' => '使用时间',
            'ip' => 'ip地址',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'req_id' => '对外id',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
