<?php

namespace common\models\common;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%common_archives}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $member_id 申请人
 * @property int|null $member_type 申请类型
 * @property int|null $certification_type 认证类型[1:公司;2:个人]
 * @property string|null $company_name 公司名称
 * @property int|null $profit_type 盈利类型[1:私立;2:国有]
 * @property string|null $unified_social_credit_code 统一社会信用代码
 * @property string|null $business_license 营业执照
 * @property string|null $business_scope 经营范围
 * @property string|null $practice_qualification_certificate 执业资格证
 * @property string|null $establish_year 成立年份
 * @property float|null $floor_space 占地面积
 * @property string|null $content 详情
 * @property string|null $corporate_realname 法人真实姓名
 * @property string|null $corporate_mobile 法人手机号码
 * @property string|null $corporate_identity_card 法人身份证
 * @property string|null $corporate_identity_card_front 法人身份证正面(国徽)
 * @property string|null $corporate_identity_card_back 法人身份证反面(人像)
 * @property string|null $bank_account_name 公司银行开户名
 * @property string|null $bank_account_number 公司银行账号
 * @property string|null $bank_branch_name 开户银行支行名称
 * @property string|null $bank_location 开户银行所在地
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Archives extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_archives}}';
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
                    'certification_type',
                    'member_id',
                    'member_type',
                    'profit_type',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [['establish_year'], 'safe'],
            [['floor_space'], 'number'],
            [['content'], 'string'],
            [['unified_social_credit_code'], 'string', 'max' => 200],
            [
                [
                    'company_name',
                    'business_license',
                    'practice_qualification_certificate',
                    'corporate_identity_card_front',
                    'corporate_identity_card_back',
                ],
                'string',
                'max' => 255,
            ],
            [['business_scope'], 'string', 'max' => 3000],
            [
                ['corporate_realname', 'bank_account_name', 'bank_account_number', 'bank_branch_name', 'bank_location'],
                'string',
                'max' => 100,
            ],
            [['corporate_mobile'], 'string', 'max' => 50],
            [['corporate_identity_card'], 'string', 'max' => 30],
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
            'member_id' => '申请人',
            'member_type' => '申请人类型',
            'certification_type' => '认证类型[1:公司;2:个人]',
            'profit_type' => '盈利类型[1:私立;2:国有]',
            'company_name' => '公司名称',
            'unified_social_credit_code' => '统一社会信用代码',
            'business_license' => '营业执照',
            'business_scope' => '经营范围',
            'practice_qualification_certificate' => '执业资格证',
            'establish_year' => '成立年份',
            'floor_space' => '占地面积',
            'content' => '详情',
            'corporate_realname' => '法人真实姓名',
            'corporate_mobile' => '法人手机号码',
            'corporate_identity_card' => '法人身份证',
            'corporate_identity_card_front' => '法人身份证正面(国徽)',
            'corporate_identity_card_back' => '法人身份证反面(人像)',
            'bank_account_name' => '公司银行开户名',
            'bank_account_number' => '公司银行账号',
            'bank_branch_name' => '开户银行支行名称',
            'bank_location' => '开户银行所在地',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
