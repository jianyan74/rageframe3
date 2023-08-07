<?php

namespace addons\Wechat\common\models;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_rule}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property string $name 规则名称
 * @property string $module 模块
 * @property string|null $data 数据
 * @property int $sort 排序
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Rule extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique','message' => '规则名称已经被占用', 'filter' => function ($query) {
                    $query->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
                }],
            [['merchant_id', 'store_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string'],
            [['name', 'module'], 'string', 'max' => 50],
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
            'store_id' => '店铺ID',
            'name' => '规则名称',
            'module' => '模块',
            'data' => '数据',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联关键字
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuleKeyword()
    {
        return $this->hasMany(RuleKeyword::class, ['rule_id' => 'id'])->orderBy('type asc');
    }

    /**
     * 关联资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasMany(Attachment::class, ['media_id' => 'data']);
    }

    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(AttachmentNews::class,['attachment_id' => 'data'])->orderBy('id asc');
    }

    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsTop()
    {
        return $this->hasOne(AttachmentNews::class,['attachment_id' => 'data'])->where(['sort' => 0]);
    }

    /**
     * 删除其他数据
     */
    public function afterDelete()
    {
        $id = $this->id;
        // 关键字删除
        RuleKeyword::deleteAll(['rule_id' => $id]);
        // 规则统计
        RuleStat::deleteAll(['rule_id' => $id]);
        // 关键字规则统计
        RuleKeywordStat::deleteAll(['rule_id' => $id]);

        parent::afterDelete();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 更新状态和排序
        RuleKeyword::updateAll(['module' => $this->module, 'sort' => $this->sort, 'status' => $this->status], ['rule_id' => $this->id]);

        parent::afterSave($insert, $changedAttributes);
    }
}
