<?php

namespace common\widgets\map;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Class MapOverlayController
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class MapOverlayController extends Controller
{
    /**
     * @param $type
     * @param $secret_key
     * @param string $lng
     * @param string $lat
     * @return string
     */
    public function actionIndex($type, $longitude = '', $latitude = '', $zoom = 12, $boxId = '', $overlay = '')
    {
        $this->layout = '@backend/views/layouts/blank';
        $secret_key = '';

        // 注册js
        $this->registerViewJs($type, $secret_key);

        // 高德 code
        $mapAMapCode = Yii::$app->services->config->backendConfig('map_amap_code');
        if (!empty($overlay)) {
            $overlay = base64_decode($overlay);
            $overlay = Json::decode($overlay);
        }

        empty($overlay) && $overlay = [];

        return $this->render('@common/widgets/map/views/overlay/' . $type, [
            'longitude' => $longitude,
            'latitude' => $latitude,
            'label' => '',
            'zoom' => $zoom,
            'boxId' => $boxId,
            'mapAMapCode' => $mapAMapCode,
            'overlay' => $overlay,
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function registerViewJs($type, $secret_key)
    {
        $view = $this->view;
        switch ($type) {
            case 'amap' :
                empty($secret_key) && $secret_key = Yii::$app->services->config->backendConfig('map_amap_key');
                $view->registerJsFile('https://webapi.amap.com/maps?v=1.4.11&plugin=AMap.PolyEditor,AMap.CircleEditor,AMap.ToolBar,AMap.Autocomplete,AMap.Geocoder&key=' . $secret_key);
                $view->registerJsFile('https://webapi.amap.com/ui/1.0/main.js?v=1.0.11');
                break;
        }

        $view->registerCss(<<<Css
    #container {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
    }
Css
        );
    }
}
