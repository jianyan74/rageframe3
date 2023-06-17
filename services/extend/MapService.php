<?php

namespace services\extend;

use Yii;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;

/**
 * Class MapService
 * @package services\extend
 */
class MapService
{
    const URL = 'http://restapi.amap.com';

    /**
     * 高德根据地址获取经纬度
     *
     * @param $address
     * @return bool|false|string[]
     * @throws \Exception
     */
    public function aMapAddressToLocation($address)
    {
        $url = self::URL . '/v3/geocode/geo?address=' . $address . '&key=' . $this->getConfigByKey('map_amap_web_key');
        $curl = new Curl();
        if ($result = $curl->get($url)) {
            $result = Json::decode($result);
            //判断是否成功
            if (!empty($result['count'])) {
                $geo = $result['geocodes']['0'];

                return [
                    'country' => $geo['country'] ?? '',
                    'province' => $geo['province'] ?? '',
                    'citycode' => $geo['citycode'] ?? '',
                    'city' => $geo['city'] ?? '',
                    'district' => $geo['district'] ?? '',
                    'township' => $geo['township'] ?? '',
                    'towncode' => $geo['towncode'] ?? '',
                    'location' => $geo['location'] ?? '',
                    'adcode' => $geo['adcode'] ?? '',
                    'level' => $geo['level'] ?? '',
                    'businessAreas' => $geo['businessAreas'] ?? '',
                ];
            }
        }

        return false;
    }

    /**
     * 高德经纬度转地址
     *
     * @param string $location 2322,12.15544
     * @return bool|mixed
     * @throws \Exception
     */
    public function aMapLocationToAddress($location)
    {
        $url = self::URL . "/v3/geocode/regeo?output=json&location=" . $location . "&key=" . $this->getConfigByKey('map_amap_web_key');
        $curl = new Curl();
        if ($result = $curl->get($url)) {
            $result = Json::decode($result);
            if (!empty($result['status']) && $result['status'] == 1) {
                $addressComponent = $result['regeocode']['addressComponent'];

                return [
                    'country' => $addressComponent['country'] ?? '',
                    'province' => $addressComponent['province'] ?? '',
                    'citycode' => $addressComponent['citycode'] ?? '',
                    'city' => $addressComponent['city'] ?? '',
                    'district' => $addressComponent['district'] ?? '',
                    'township' => $addressComponent['township'] ?? '',
                    'towncode' => $addressComponent['towncode'] ?? '',
                    'location' => $addressComponent['location'] ?? '',
                    'adcode' => $addressComponent['adcode'] ?? '',
                    'level' => $addressComponent['level'] ?? '',
                    'businessAreas' => $addressComponent['businessAreas'] ?? '',
                    'streetNumber' => $addressComponent['streetNumber'] ?? '',
                    'formatted_address' => $result['regeocode']['formatted_address']
                ];
            }
        }

        return false;
    }

    /**
     * 默认后台拿 key
     *
     * @param $key
     * @return string|null
     */
    protected function getConfigByKey($key)
    {
        return Yii::$app->services->config->backendConfig($key);
    }
}
