<?php

namespace common\widgets\map;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use common\helpers\StringHelper;

/**
 * Class MapCity
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class MapCity extends InputWidget
{
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

        // 高德 code
        empty($this->secret_code) && $this->secret_code = Yii::$app->services->config->backendConfig('map_amap_code');
        empty($this->secret_key) && $this->secret_key = Yii::$app->services->config->backendConfig('map_amap_key');

        return $this->render('city/' . $this->type, [
            'name' => $name,
            'value' => $value,
            'secret_key' => $this->secret_key,
            'secret_code' => $this->secret_code,
            'boxId' => StringHelper::uuid('uniqid')
        ]);
    }
}
