<?php

namespace common\models\common;

use common\traits\Tree;
use common\behaviors\MerchantStoreBehavior;

/**
 * This is the model class for table "{{%common_attachment_cate}}".
 *
 * @property int $id 主键
 * @property int|null $merchant_id 商户id
 * @property string|null $title 标题
 * @property int|null $sort 排序
 * @property int|null $level 级别
 * @property int|null $pid 上级id
 * @property string|null $tree 树
 * @property int|null $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class AttachmentCate extends \common\models\base\BaseModel
{
    use Tree, MerchantStoreBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_attachment_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['title'], 'required'],
            [['tree'], 'string', 'max' => 500],
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
            'store_id' => '店铺id',
            'title' => '标题',
            'sort' => '排序',
            'level' => '级别',
            'pid' => '上级id',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
