<?php

namespace common\widgets\map;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use common\helpers\StringHelper;

/**
 * 地图范围选择(门店可配送范围)
 *
 * Class MapOverlay
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class MapOverlay extends InputWidget
{
    /**
     * @var string
     */
    public $longitude = '116.456270';

    /**
     * @var string
     */
    public $latitude = '39.919990';

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
            }
        } catch (\Exception $e) {
            $value = [];
        }

        empty($value) && $value = [];

        $this->view->registerJsFile('@baseResources/plugins/jquery-base64/jquery.base64.js');

        return $this->render('overlay/index', [
            'name' => $name,
            'value' => $value,
            'type' => $this->type,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'secret_key' => $this->secret_key,
            'secret_code' => $this->secret_code,
            'boxId' => StringHelper::uuid('uniqid')
        ]);
    }
}
