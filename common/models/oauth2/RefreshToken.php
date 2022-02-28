<?php

namespace common\models\oauth2;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%oauth2_refresh_token}}".
 *
 * @property string $refresh_token
 * @property int|null $merchant_id 商户id
 * @property string $client_id 授权ID
 * @property string|null $member_id 用户ID
 * @property string $expires 有效期
 * @property string|null $scope 授权权限
 * @property string|null $grant_type 组别
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class RefreshToken extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_refresh_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'client_id', 'expires'], 'required'],
            [['expires', 'scope'], 'safe'],
            [['refresh_token'], 'string', 'max' => 80],
            [['client_id'], 'string', 'max' => 64],
            [['member_id'], 'string', 'max' => 100],
            [['grant_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'refresh_token' => 'Refresh Token',
            'merchant_id' => '商户id',
            'client_id' => '授权ID',
            'member_id' => '用户ID',
            'expires' => '有效期',
            'scope' => '授权权限',
            'grant_type' => '组别',
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
