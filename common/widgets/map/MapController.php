<?php

namespace common\widgets\map;

use Yii;
use yii\web\Controller;

/**
 * Class MapController
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class MapController extends Controller
{
    /**
     * @param $type
     * @param $secret_key
     * @param string $lng
     * @param string $lat
     * @return string
     */
    public function actionMap($type, $lng = '', $lat = '', $zoom = 12, $boxId = '', $defaultSearchAddress = '')
    {
        $this->layout = '@backend/views/layouts/blank';
        $secret_key = '';

        // 注册js
        $this->registerViewJs($type, $secret_key);

        // 高德 code
        $mapAMapCode = Yii::$app->services->config->backendConfig('map_amap_code');

        return $this->render('@common/widgets/map/views/map/' . $type, [
            'lng' => $lng,
            'lat' => $lat,
            'zoom' => $zoom,
            'boxId' => $boxId,
            'mapAMapCode' => $mapAMapCode,
            'defaultSearchAddress' => $defaultSearchAddress,
        ]);
    }

    /**
     * @param $type
     * @param string $lng
     * @param string $lat
     * @param int $zoom
     * @param int $boxId
     * @return string
     */
    public function actionMapView($type, $lng = '', $lat = '', $label = '', $zoom = 12)
    {
        $secret_key = '';
        // 注册js
        $this->registerViewJs($type, $secret_key);

        return $this->render('@common/widgets/map/views/map/detail/' . $type, [
            'lng' => $lng,
            'lat' => $lat,
            'label' => $label,
            'zoom' => $zoom,
        ]);
    }

    /**
     * @param $type
     * @param string $lng
     * @param string $lat
     * @param int $zoom
     * @param int $boxId
     * @return string
     */
    public function actionRidingRoute($type, $lng = '', $lat = '', $label = '', $lng2 = '', $lat2 = '', $label2 = '', $zoom = 12)
    {
        $secret_key = '';
        // 注册js
        $this->registerViewJs($type, $secret_key);

        return $this->render('@common/widgets/map/views/map/route/' . $type, [
            'lng' => $lng,
            'lat' => $lat,
            'label' => $label,
            'lng2' => $lng2,
            'lat2' => $lat2,
            'label2' => $label2,
            'zoom' => $zoom,
        ]);
    }

    /**
     * 手动输入
     *
     * @param string $lng
     * @param string $lat
     * @param int $boxId
     * @return string
     */
    public function actionInput($lng = '', $lat = '', $boxId = 12)
    {
        return $this->renderAjax('@common/widgets/map/views/map/input', [
            'lng' => $lng,
            'lat' => $lat,
            'boxId' => $boxId,
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function registerViewJs($type, $secret_key)
    {
        $view = $this->view;
        switch ($type) {
            case 'baidu' :
                empty($secret_key) && $secret_key = Yii::$app->services->config->backendConfig('map_baidu_ak');
                $view->registerJsFile('https://api.map.baidu.com/api?v=2.0&ak=' . $secret_key);
                break;
            case 'amap' :
                empty($secret_key) && $secret_key = Yii::$app->services->config->backendConfig('map_amap_key');
                $view->registerJsFile('https://webapi.amap.com/maps?v=1.4.11&plugin=AMap.ToolBar,AMap.Autocomplete,AMap.PlaceSearch,AMap.Geocoder&key=' . $secret_key);
                $view->registerJsFile('https://webapi.amap.com/ui/1.0/main.js?v=1.0.11');
                break;
            case 'tencent' :
                empty($secret_key) && $secret_key = Yii::$app->services->config->backendConfig('map_tencent_key');
                $view->registerJsFile('https://map.qq.com/api/js?v=2.exp&libraries=place&key=' . $secret_key);
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

    .search {
        position: absolute;
        width: 400px;
        top: 0;
        left: 50%;
        padding: 5px;
        margin-left: -200px;
    }
Css
        );
    }
}
