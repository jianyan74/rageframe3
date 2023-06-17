<?php

namespace common\models\member;

use Yii;
use common\enums\StatusEnum;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%member_auth}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property int|null $member_type 1:会员;2:后台管理员;3:商家管理员
 * @property string|null $unionid 唯一ID
 * @property string|null $oauth_client 授权组别
 * @property string|null $oauth_client_user_id 授权id
 * @property int|null $gender 性别[0:未知;1:男;2:女]
 * @property string|null $nickname 昵称
 * @property string|null $head_portrait 头像
 * @property string|null $birthday 生日
 * @property string|null $country 国家
 * @property string|null $province 省
 * @property string|null $city 市
 * @property int|null $is_addon 是否插件
 * @property string|null $addon_name 插件名称
 * @property int|null $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 * @property $member Member
 */
class Auth extends \common\models\base\BaseModel
{
    use HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'member_type', 'gender', 'is_addon', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['unionid'], 'string', 'max' => 64],
            [['oauth_client'], 'string', 'max' => 20],
            [['oauth_client_user_id', 'nickname', 'country', 'province', 'city'], 'string', 'max' => 100],
            [['head_portrait', 'addon_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'member_type' => '1:会员;2:后台管理员;3:商家管理员',
            'unionid' => '唯一ID',
            'oauth_client' => '授权组别',
            'oauth_client_user_id' => '授权ID',
            'gender' => '性别',
            'nickname' => '昵称',
            'head_portrait' => '头像',
            'birthday' => '生日',
            'country' => '国家',
            'province' => '省',
            'city' => '市',
            'is_addon' => '是否插件',
            'addon_name' => '插件名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
