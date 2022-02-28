<?php

namespace common\models\oauth2;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\behaviors\MerchantBehavior;
use common\models\base\User;

/**
 * This is the model class for table "{{%oauth2_access_token}}".
 *
 * @property int $id
 * @property string $access_token 授权Token
 * @property int|null $merchant_id 商户id
 * @property string|null $auth_key 授权令牌
 * @property string $client_id 授权ID
 * @property string|null $member_id 用户ID
 * @property string $expires 有效期
 * @property string|null $scope 授权权限
 * @property string|null $grant_type 组别
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AccessToken extends User
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_access_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['access_token', 'client_id', 'expires'], 'required'],
            [['expires', 'scope'], 'safe'],
            [['access_token'], 'string', 'max' => 80],
            [['client_id'], 'string', 'max' => 64],
            [['member_id'], 'string', 'max' => 100],
            [['grant_type'], 'string', 'max' => 30],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => '授权Token',
            'merchant_id' => '商户id',
            'auth_key' => '授权令牌',
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

    /**
     * @return array
     */
    public function behaviors()
    {
        $merchant_id = Yii::$app->services->merchant->getNotNullId();

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
                ],
                'value' => $merchant_id,
            ]
        ];
    }
}
