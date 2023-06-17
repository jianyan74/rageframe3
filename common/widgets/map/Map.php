<?php

namespace common\widgets\map;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use common\helpers\StringHelper;

/**
 * 地图经纬度选择器
 *
 * Class Map
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class Map extends InputWidget
{
    /**
     * 默认地址
     *
     * @var bool
     */
    public $defaultSearchAddress = '北京';

    /**
     * 秘钥
     *
     * @var string
     */
    public $secret_key = '';

    /**
     * 申请的安全密钥(高德)
     *
     * @var string
     */
    public $secret_code = '';

    /**
     * 类型
     *
     * 默认高德
     *
     * amap 高德
     * tencent 腾讯
     * baidu 高德
     *
     * @var string
     */
    public $type = 'amap';

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        try {
            if ($value && !is_array($value)) {
                $value = json_decode($value, true);
                empty($value) && $value = unserialize($value);
                empty($value) && $value = [];
            }
        } catch (\Exception $e) {
            $value = [];
        }

        // 显示地址
        $address = empty($value) ? '' : implode(',', [$value['longitude'] ?? '', $value['latitude'] ?? '']);
        $defaultValue = [
            'longitude' => $value['longitude'] ?? '116.456270',
            'latitude' => $value['latitude'] ?? '39.919990',
        ];

        return $this->render('map/index', [
            'name' => $name,
            'value' => $defaultValue,
            'type' => $this->type,
            'secret_key' => $this->secret_key,
            'secret_code' => $this->secret_code,
            'address' => $address,
            'defaultSearchAddress' => $this->defaultSearchAddress,
            'boxId' => StringHelper::uuid('uniqid')
        ]);
    }
}
