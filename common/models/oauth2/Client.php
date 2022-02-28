<?php

namespace common\models\oauth2;

use Yii;

/**
 * This is the model class for table "{{%oauth2_client}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property string $title 标题
 * @property string $client_id
 * @property string $client_secret
 * @property string|null $redirect_uri 回调Url
 * @property string|null $remark 备注
 * @property string|null $group 组别
 * @property string|null $scope 授权
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Client extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth2_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id'], 'unique'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['client_id', 'client_secret', 'title'], 'required'],
            [['scope'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['client_id', 'client_secret'], 'string', 'max' => 64],
            [['redirect_uri'], 'string', 'max' => 2000],
            [['remark'], 'string', 'max' => 200],
            [['group'], 'string', 'max' => 30],
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
            'title' => '授权对象',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'redirect_uri' => '回调地址',
            'remark' => '备注',
            'group' => '组别',
            'scope' => '授权',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
