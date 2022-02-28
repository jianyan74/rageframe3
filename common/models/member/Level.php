<?php

namespace common\models\member;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%member_level}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property int|null $level 等级（数字越大等级越高）
 * @property string|null $name 等级名称
 * @property string|null $icon 等级图标
 * @property string|null $cover 等级背景图
 * @property string|null $detail 等级介绍
 * @property float|null $money 消费额度满足则升级
 * @property int|null $integral 消费积分满足则升级
 * @property int|null $growth 成长值满足则升级
 * @property float|null $discount 折扣
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Level extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_level}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'discount', 'name'], 'required'],
            [['level'], 'unique', 'filter' => function (ActiveQuery $query) {
                return $query->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
            }],
            [['merchant_id', 'level', 'integral', 'growth', 'status', 'created_at', 'updated_at'], 'integer'],
            [['money'], 'number', 'min' => 0],
            [['integral', 'growth'], 'integer', 'min' => 0],
            [['discount'], 'number', 'min' => 0, 'max' => 10],
            [['name', 'icon', 'cover', 'detail'], 'string', 'max' => 255],
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
            'level' => '等级',
            'name' => '等级名称',
            'icon' => '等级图标',
            'cover' => '等级背景图',
            'detail' => '等级介绍',
            'money' => '消费额度满足则升级',
            'integral' => '消费积分满足则升级',
            'growth' => '成长值满足则升级',
            'discount' => '折扣',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
