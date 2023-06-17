<?php

namespace common\models\rbac;

use Yii;
use common\traits\Tree;
use common\helpers\TreeHelper;

/**
 * This is the model class for table "{{%rbac_auth_role}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string $title 标题
 * @property string $app_id 应用
 * @property int|null $pid 上级id
 * @property int|null $level 级别
 * @property int|null $sort 排序
 * @property int|null $operating_type 运营类型
 * @property double|null $annual_fee 年费
 * @property string $tree 树
 * @property int|null $is_default 是否默认角色
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class AuthRole extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rbac_auth_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'isUniqueTitle'],
            [['title'], 'trim'],
            [['merchant_id', 'store_id', 'operating_type', 'pid', 'level', 'sort', 'is_default', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
            [['annual_fee'], 'number', 'min' => 0],
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
            'store_id' => '店铺ID',
            'title' => '角色名称',
            'app_id' => '应用',
            'pid' => '父级',
            'level' => '级别',
            'sort' => '排序',
            'annual_fee' => '年费',
            'operating_type' => '运营类型',
            'tree' => '树',
            'is_default' => '是否默认角色',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function isUniqueTitle($attribute)
    {
        $merchant_id = $this->merchant_id;
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();

        $model = self::find()->where([
            'merchant_id' => $merchant_id,
            'title' => $this->title
        ])->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '角色名称已存在');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChild()
    {
        return $this->hasMany(AuthItemChild::class, ['role_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $childIds = self::find()
            ->select(['id'])
            ->where(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false])
            ->column();

        $childIds[] = $this->id;

        AuthItemChild::deleteAll(['in', 'role_id', $childIds]);
        AuthAssignment::deleteAll(['in', 'role_id', $childIds]);

        $this->autoDeleteTree();

        return parent::beforeDelete();
    }
}
