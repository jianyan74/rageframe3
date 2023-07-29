<?php

namespace addons\WechatMini\common\models\video\common;

use addons\WechatMini\common\enums\video\AuditTypeEnum;

/**
 * This is the model class for table "{{%addon_wechat_capabilities_base_cat}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property string $tree 树
 * @property string $qualification 类目资质
 * @property int $qualification_type 类目资质类型,0:不需要,1:必填,2:选填
 * @property string $product_qualification 商品资质
 * @property int $product_qualification_type 商品资质类型,0:不需要,1:必填,2:选填
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Cate extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_mini_video_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'level', 'pid', 'qualification_type', 'product_qualification_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tree'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['qualification', 'product_qualification', 'tree_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'sort' => '排序',
            'level' => '级别',
            'pid' => '上级id',
            'tree' => '树',
            'tree_title' => '全称',
            'qualification' => '类目资质',
            'qualification_type' => '类目资质类型', // 0:不需要,1:必填,2:选填
            'product_qualification' => '商品资质',
            'product_qualification_type' => '商品资质类型', // 0:不需要,1:必填,2:选填
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudit()
    {
        return $this->hasOne(Audit::class, ['map_id' => 'id'])->andWhere(['audit_type' => AuditTypeEnum::CATE])->orderBy('id desc');
    }
}
