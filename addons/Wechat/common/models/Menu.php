<?php

namespace addons\Wechat\common\models;

use Yii;
use common\enums\StatusEnum;
use addons\Wechat\common\enums\MenuTypeEnum;

/**
 * This is the model class for table "{{%addon_wechat_menu}}".
 *
 * @property int $id 公众号id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺ID
 * @property int|null $menu_id 微信菜单id
 * @property int|null $type 1:默认菜单；2个性化菜单
 * @property string|null $title 标题
 * @property int|null $tag_id 标签id
 * @property int|null $client_platform_type 手机系统
 * @property string|null $menu_data 微信菜单
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Menu extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['merchant_id', 'store_id', 'menu_id', 'type', 'tag_id', 'client_platform_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['menu_data'], 'safe'],
            [['title'], 'string', 'max' => 30],
            [['title'], 'verifyEmpty'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '公众号id',
            'merchant_id' => '商户id',
            'store_id' => '店铺ID',
            'menu_id' => '微信菜单id',
            'type' => '1:默认菜单；2个性化菜单',
            'title' => '标题',
            'tag_id' => '标签id',
            'client_platform_type' => '手机系统',
            'menu_data' => '微信菜单',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 验证是否全部为空
     *
     * @return bool|void
     */
    public function verifyEmpty()
    {
        if(
            $this->type == MenuTypeEnum::INDIVIDUATION &&
            empty($this->tag_id) &&
            empty($this->client_platform_type)
        ) {
            $this->addError('sex', '菜单显示对象至少要有一个匹配信息是不为空的');
        }
    }


    /**
     * 修改默认菜单状态
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->status = StatusEnum::ENABLED;
        return parent::beforeSave($insert);
    }

    /**
     * 修改其他菜单状态
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->type == MenuTypeEnum::CUSTOM) {
            self::updateAll(['status' => StatusEnum::DISABLED],
                [
                    'and',
                    ['not in', 'id', [$this->id]],
                    ['type' => MenuTypeEnum::CUSTOM],
                    ['merchant_id' => Yii::$app->services->merchant->getNotNullId()]
                ]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
