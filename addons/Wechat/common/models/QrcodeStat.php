<?php

namespace addons\Wechat\common\models;

use common\models\member\Auth;

/**
 * This is the model class for table "{{%addon_wechat_qrcode_stat}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $qrcord_id 二维码id
 * @property string|null $openid 微信openid
 * @property int|null $type 1:关注;2:扫描
 * @property string|null $name 场景名称
 * @property string|null $scene_str 场景值
 * @property int|null $scene_id 场景ID
 * @property int|null $status 状态
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class QrcodeStat extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_qrcode_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'qrcord_id', 'type', 'scene_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'name'], 'string', 'max' => 50],
            [['scene_str'], 'string', 'max' => 64],
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
            'qrcord_id' => '二维码id',
            'openid' => '微信openid',
            'type' => '1:关注;2:扫描',
            'name' => '场景名称',
            'scene_str' => '场景值',
            'scene_id' => '场景ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFans()
    {
        return $this->hasOne(Fans::class,['openid' => 'openid']);
    }

    /**
     * 关联授权
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::class, ['oauth_client_user_id' => 'openid']);
    }
}
