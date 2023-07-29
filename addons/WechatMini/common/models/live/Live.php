<?php

namespace addons\WechatMini\common\models\live;

use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;
use common\models\base\BaseModel;

/**
 * This is the model class for table "rf_addon_wechat_mini_live".
 *
 * @property int $id 组合商品id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string|null $name 直播间名称
 * @property int|null $roomid 直播间ID
 * @property string|null $cover_img 直播封面
 * @property string|null $share_img 直播间分享图链接
 * @property int|null $live_status 直播间状态
 * @property int|null $start_time 开始时间
 * @property int|null $end_time 结束时间
 * @property string|null $anchor_name 主播名
 * @property string|null $anchor_wechat 主播微信
 * @property string|null $sub_anchor_wechat 主播副号微信号
 * @property int|null $live_type 直播类型，1 推流 0 手机直播
 * @property string|null $creater_openid 创建者openid
 * @property string|null $creater_wechat 创建者微信号
 * @property int|null $close_like 是否关闭点赞[0:开启;1:关闭]
 * @property int|null $close_goods 是否关闭货架[0:开启;1:关闭]
 * @property int|null $close_comment 是否关闭评论[0:开启;1:关闭]
 * @property int|null $close_share 是否关闭分享[0:开启;1:关闭]
 * @property int|null $close_kf 是否关闭客服[0:开启;1:关闭]
 * @property int|null $close_replay 是否关闭回放[0:开启;1:关闭]
 * @property int|null $is_feeds_public 是否开启官方收录[1:开启;0:关闭]
 * @property string|null $feeds_img 官方收录封面
 * @property int|null $total 拉取房间总数
 * @property string|null $playback 回放视频
 * @property string|null $push_addr 直播间推流地址
 * @property string|null $assistant 小助手
 * @property string|null $cover_media 直播封面资源ID
 * @property string|null $share_media 直播间分享图资源ID
 * @property string|null $feeds_media 官方收录封面资源ID
 * @property string|null $share_path 分享
 * @property string|null $qrcode_url 二维码地址
 * @property int|null $is_recommend 是否推荐
 * @property int|null $is_stick 是否置顶
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Live extends BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_live}}';
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
                    'roomid',
                    'live_status',
                    'live_type',
                    'close_like',
                    'close_goods',
                    'close_comment',
                    'close_share',
                    'close_kf',
                    'close_replay',
                    'is_feeds_public',
                    'total',
                    'is_recommend',
                    'is_stick',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [['playback', 'assistant', 'share_path'], 'safe'],
            [
                [
                    'name',
                    'cover_img',
                    'share_img',
                    'anchor_name',
                    'feeds_img',
                    'cover_media',
                    'share_media',
                    'feeds_media',
                ],
                'string',
                'max' => 200,
            ],
            [['anchor_wechat', 'sub_anchor_wechat', 'creater_openid', 'creater_wechat'], 'string', 'max' => 50],
            [['push_addr'], 'string', 'max' => 500],
            [['qrcode_url'], 'string', 'max' => 255],
            // 自定义
            [['name', 'anchor_name', 'anchor_wechat', 'cover_img', 'share_img', 'feeds_img'], 'required'],
            [['name'], 'string', 'min' => 3, 'max' => 17],
            [['anchor_name'], 'string', 'min' => 2, 'max' => 15],
            [['start_time', 'end_time'], 'required'],
            [['end_time'], 'comparisonEndTime'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '组合商品id',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'name' => '直播间名称',
            'roomid' => '直播间ID',
            'cover_img' => '直播封面',
            'share_img' => '直播间分享图',
            'live_status' => '直播间状态',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'anchor_name' => '主播名',
            'anchor_wechat' => '主播微信号',
            'sub_anchor_wechat' => '主播副号微信号',
            'creater_wechat' => '创建者微信号',
            'live_type' => '直播类型', // 1:推流;0:手机直播
            'creater_openid' => '创建者openid',
            'close_like' => '点赞', // 0:开启;1:关闭
            'close_goods' => '货架', // 0:开启;1:关闭
            'close_comment' => '评论', // 0:开启;1:关闭
            'close_share' => '分享', // 0:开启;1:关闭
            'close_kf' => '客服', // 0:开启;1:关闭
            'close_replay' => '直播回放', // 0:开启;1:关闭
            'is_feeds_public' => '官方收录', // 1:开启;0:关闭
            'feeds_img' => '官方收录封面',
            'total' => '拉取房间总数',
            'playback' => '回放视频',
            'push_addr' => '直播间推流地址',
            'share_link' => '直播间分享二维码',
            'assistant' => '小助手',
            'is_stick' => '是否置顶',
            'is_recommend' => '是否推荐',
            'status' => '状态', // [-1:删除;0:禁用;1启用]
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function comparisonEndTime($attribute)
    {
        $start_time = StringHelper::dateToInt($this->start_time);
        $end_time = StringHelper::dateToInt($this->end_time);

        if ($start_time >= $end_time) {
            $this->addError($attribute, '结束时间必须大于开始时间');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsMap()
    {
        return $this->hasMany(GoodsMap::class, ['roomid' => 'roomid']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);

        return parent::beforeSave($insert);
    }
}
