<?php

namespace addons\RfDemo\common\forms;

use yii\helpers\Json;
use yii\db\Expression;
use common\helpers\ArrayHelper;
use addons\RfDemo\common\models\Curd;
use addons\RfDemo\common\models\CurdMap;

/**
 * Class CurdForm
 * @package addons\RfDemo\common\forms
 */
class CurdForm extends Curd
{
    /**
     * 地图定位
     *
     * @var array
     */
    public $longitude_and_latitude = [];

    /**
     * 地图范围
     *
     * @var
     */
    public $map_overlay;

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['map_overlay'], 'safe']
        ]);
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'longitude_and_latitude' => '地图位置',
            'map_overlay' => '地图范围',
        ]);
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        $this->map_overlay = CurdMap::find()
            ->select(['name', 'type', 'path', 'radius', 'shipping_fee'])
            ->where(['curd_id' => $this->id])
            ->asArray()
            ->all();

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->map_overlay) && !is_array($this->map_overlay)) {
            $this->map_overlay = Json::decode($this->map_overlay);
        }

        CurdMap::deleteAll(['curd_id' => $this->id]);

        foreach ($this->map_overlay as $item) {
            $model = new CurdMap();
            $model->curd_id = $this->id;
            $model->attributes = $item;
            $path = [];
            foreach ($item['path'] as $value) {
                $path[] = implode(' ', $value);
            }

            // 说明
            // 线串至少有两个点
            // 多边形至少有一个环
            // 多边形环关闭(第一个和最后一个点相同)
            // 多边形环至少有 4 个点(最小多边形是一个三角形，第一个和最后一个点相同)
            // 集合不为空(除了GeometryCollection)
            $path[] = $path[0];
            $path = implode(',', $path);
            $model->polygon = new Expression("GeomFromText(:point)", [':point' => 'POLYGON((' . $path . '))']);
            $model->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
