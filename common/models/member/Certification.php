<?php

namespace common\models\member;

use Yii;

/**
 * This is the model class for table "{{%member_certification}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 用户id
 * @property int|null $member_type 用户类型
 * @property string|null $realname 真实姓名
 * @property string|null $identity_card 身份证号码
 * @property string|null $identity_card_front 身份证国徽面
 * @property string|null $identity_card_back 身份证人像面
 * @property string|null $gender 性别
 * @property string|null $birthday 生日
 * @property string|null $address 地址
 * @property int|null $front_is_fake 正面是否是复印件
 * @property int|null $back_is_fake 背面是否是复印件
 * @property string|null $nationality 民族 
 * @property string|null $start_date 有效期起始时间
 * @property string|null $end_date 有效期结束时间
 * @property string|null $issue 签发机关 
 * @property int|null $is_self 自己认证
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Certification extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_certification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'member_type', 'front_is_fake', 'back_is_fake', 'is_self', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday', 'start_date', 'end_date'], 'safe'],
            [['realname', 'nationality'], 'string', 'max' => 100],
            [['identity_card'], 'string', 'max' => 50],
            [['identity_card_front', 'address', 'identity_card_back', 'issue'], 'string', 'max' => 200],
            [['gender'], 'string', 'max' => 10],
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
            'member_type' => '用户类型',
            'realname' => '真实姓名',
            'identity_card' => '身份证号码',
            'identity_card_front' => '身份证国徽面',
            'identity_card_back' => '身份证人像面',
            'address' => '地址',
            'gender' => '性别',
            'birthday' => '生日',
            'front_is_fake' => '正面是否是复印件',
            'back_is_fake' => '背面是否是复印件',
            'nationality' => '民族 ',
            'start_date' => '有效期起始时间',
            'end_date' => '有效期结束时间',
            'issue' => '签发机关 ',
            'is_self' => '自己认证',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
