<?php

namespace addons\Wechat\common\models;

use yii\db\ActiveQuery;
use common\helpers\StringHelper;
use common\behaviors\MerchantStoreBehavior;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%addon_wechat_mass_record}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $msg_id 微信消息id
 * @property string|null $msg_data_id 图文消息数据id
 * @property int|null $tag_id 标签id
 * @property string|null $tag_name 标签名称
 * @property int|null $fans_num 粉丝数量
 * @property string|null $module 模块类型
 * @property string|null $data
 * @property int|null $send_type 发送类别 1立即发送2定时发送
 * @property int|null $send_time 发送时间
 * @property int|null $send_status 0未发送 1已发送
 * @property int|null $final_send_time 最终发送时间
 * @property string|null $error_content 报错原因
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at
 * @property int $updated_at 修改时间
 */
class MassRecord extends BaseModel
{
    use MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mass_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id'], 'required'],
            [
                [
                    'merchant_id',
                    'store_id',
                    'msg_id',
                    'tag_id',
                    'fans_num',
                    'send_type',
                    'send_status',
                    'final_send_time',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [['data', 'error_content'], 'string'],
            [['msg_data_id'], 'string', 'max' => 10],
            [['tag_name', 'module'], 'string', 'max' => 50],
            [['send_time'], 'safe'],
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
            'msg_id' => '微信消息id',
            'msg_data_id' => '图文消息数据id',
            'tag_id' => '标签',
            'tag_name' => '标签名称',
            'fans_num' => '粉丝数量',
            'module' => '模块类型',
            'data' => 'Data',
            'send_type' => '发送类别 1立即发送2定时发送',
            'send_time' => '发送时间',
            'send_status' => '0未发送 1已发送',
            'final_send_time' => '最终发送时间',
            'error_content' => '报错原因',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => 'Created At',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::class, ['id' => 'data']);
    }

    public function beforeSave($insert)
    {
        $this->send_time = StringHelper::dateToInt($this->send_time);

        return parent::beforeSave($insert);
    }
}
