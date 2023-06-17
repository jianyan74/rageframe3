<?php

namespace common\models\api;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UnauthorizedHttpException;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\models\rbac\AuthAssignment;
use common\models\base\User;

/**
 * This is the model class for table "{{%api_access_token}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户ID
 * @property int|null $store_id 店铺ID
 * @property string|null $refresh_token 刷新令牌
 * @property string|null $access_token 授权令牌
 * @property int|null $member_id 用户id
 * @property int|null $member_type 用户类型
 * @property string|null $group 组别
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class AccessToken extends User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%api_access_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'store_id', 'member_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'access_token'], 'string', 'max' => 60],
            [['group'], 'string', 'max' => 100],
            [['access_token'], 'unique'],
            [['refresh_token'], 'unique'],
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
            'refresh_token' => '刷新令牌',
            'access_token' => '授权令牌',
            'member_id' => '用户id',
            'member_type' => '用户类型',
            'group' => '组别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|mixed|ActiveRecord|\yii\web\IdentityInterface|null
     * @throws UnauthorizedHttpException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // 判断验证token有效性是否开启
        if (Yii::$app->params['user.accessTokenValidity'] === true) {
            $timestamp = (int)substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.accessTokenExpire'];

            // 验证有效期
            if ($timestamp + $expire <= time()) {
                throw new UnauthorizedHttpException('您的登录验证已经过期，请重新登录');
            }
        }

        // 优化版本到缓存读取用户信息 注意需要开启服务层的cache
        return Yii::$app->services->apiAccessToken->getTokenToCache($token, $type);
    }

    /**
     * @param $token
     * @param null $group
     * @return AccessToken|\common\models\base\User|null
     */
    public static function findIdentityByRefreshToken($token, $group = null)
    {
        return static::findOne(['group' => $group, 'refresh_token' => $token, 'status' => StatusEnum::ENABLED]);
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * 关联授权角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'member_id'])->where(['app_id' => Yii::$app->id]);
    }
}
