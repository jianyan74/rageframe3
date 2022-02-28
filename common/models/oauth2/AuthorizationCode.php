<?php

namespace common\models\oauth2;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%oauth2_authorization_code}}".
 *
 * @property string $authorization_code
 * @property int|null $merchant_id 商户id
 * @property string $client_id 授权ID
 * @property string|null $member_id 用户ID
 * @property string|null $redirect_uri 回调url
 * @property string $expires 有效期
 * @property string|null $scope 授权权限
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AuthorizationCode extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_authorization_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['authorization_code', 'client_id', 'expires'], 'required'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['expires', 'scope'], 'safe'],
            [['authorization_code', 'member_id'], 'string', 'max' => 100],
            [['client_id'], 'string', 'max' => 64],
            [['redirect_uri'], 'string', 'max' => 2000],
            [['authorization_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'authorization_code' => 'Authorization Code',
            'merchant_id' => '商户id',
            'client_id' => '授权ID',
            'member_id' => '用户ID',
            'redirect_uri' => '回调url',
            'expires' => '有效期',
            'scope' => '授权权限',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['client_id' => 'client_id']);
    }
}
