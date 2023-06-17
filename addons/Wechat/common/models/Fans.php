<?php

namespace addons\Wechat\common\models;

use common\models\member\Auth;
use common\models\member\Member;
use common\traits\HasOneMember;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_fans}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户ID
 * @property int|null $store_id 店铺ID
 * @property int|null $member_id 用户id
 * @property string|null $unionid 唯一公众号ID
 * @property string $openid openid
 * @property string|null $nickname 昵称
 * @property string|null $head_portrait 头像
 * @property int|null $follow 是否关注[1:关注;0:取消关注]
 * @property int|null $follow_time 关注时间
 * @property int|null $unfollow_time 取消关注时间
 * @property int|null $group_id 分组id
 * @property string|null $tag 标签
 * @property string|null $last_longitude 最近经纬度上报
 * @property string|null $last_latitude 最近经纬度上报
 * @property string|null $last_address 最近经纬度上报地址
 * @property int|null $last_updated 最后更新时间
 * @property string|null $remark 粉丝备注
 * @property string|null $subscribe_scene 关注来源
 * @property string|null $qr_scene 二维码扫码场景
 * @property string|null $qr_scene_str 二维码扫码场景描述
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class Fans extends \yii\db\ActiveRecord
{
    use MerchantBehavior, HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_fans}}';
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
                    'follow',
                    'follow_time',
                    'unfollow_time',
                    'group_id',
                    'last_updated',
                    'status',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [['tag'], 'safe'],
            [['unionid', 'qr_scene_str'], 'string', 'max' => 64],
            [['openid', 'nickname', 'subscribe_scene'], 'string', 'max' => 50],
            [['head_portrait'], 'string', 'max' => 255],
            [['last_longitude', 'last_latitude'], 'string', 'max' => 10],
            [['last_address'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 30],
            [['qr_scene'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'store_id' => '店铺ID',
            'member_id' => '用户id',
            'unionid' => '唯一公众号ID',
            'openid' => 'openId',
            'nickname' => '昵称',
            'head_portrait' => '头像',
            'follow' => '关注状态', // [1:关注;0:取消关注]
            'follow_time' => '关注时间',
            'unfollow_time' => '取消关注时间',
            'group_id' => '分组id',
            'tag' => '标签',
            'last_longitude' => '最近经纬度上报',
            'last_latitude' => '最近经纬度上报',
            'last_address' => '最近经纬度上报地址',
            'last_updated' => '最后更新时间',
            'remark' => '粉丝备注',
            'subscribe_scene' => '关注来源',
            'qr_scene' => '二维码扫码场景',
            'qr_scene_str' => '二维码扫码场景描述',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联会员
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * 关联授权
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::class, ['oauth_client_user_id' => 'openid']);
    }

    /**
     * 标签关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(FansTagMap::class, ['fans_id' => 'id']);
    }
}
