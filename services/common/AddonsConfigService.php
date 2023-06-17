<?php

namespace services\common;

use Yii;
use common\enums\AppEnum;
use common\models\common\AddonsConfig;

/**
 * Class AddonsConfigService
 * @package services\common
 */
class AddonsConfigService
{
    /**
     * @param $name
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByName($name, $app_id, $merchant_id = 0)
    {
        return AddonsConfig::find()
            ->where(['addon_name' => $name, 'app_id' => $app_id])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->one();
    }

    /**
     * 查找缓存配置
     *
     * @param string $name
     * @param int $merchant_id
     * @param false $noCache
     * @return array|mixed
     */
    public function findConfigByCache($name = '', $merchant_id = 0, $noCache = false)
    {
        empty($name) && $name = Yii::$app->params['addon']['name'];
        $app_id = $merchant_id > 0 ? AppEnum::MERCHANT : AppEnum::BACKEND;

        $cacheKey = 'rfAddonConfig'. ':' . $app_id . ':' . $name;
        if (!$noCache && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $config = $this->findByName($name, $app_id, $merchant_id);
        Yii::$app->cache->set($cacheKey, $config->data ?? [], 60);

        return $config->data ?? [];
    }

    /**
     * 查询配置
     *
     * @param string $name
     * @param string $app_id
     * @param int $merchant_id
     * @return array|mixed
     */
    public function getConfig($name = '', $merchant_id = 0)
    {
        empty($name) && $name = Yii::$app->params['addon']['name'];
        $app_id = $merchant_id > 0 ? AppEnum::MERCHANT : AppEnum::BACKEND;

        $config = $this->findByName($name, $app_id, $merchant_id);

        return $config->data ?? [];
    }

    /**
     * 写入配置
     *
     * @param array $config
     * @param string $name
     * @param int $merchant_id
     * @param int $store_id
     * @return array|mixed
     */
    public function setConfig($config = [], $name = '', $merchant_id = 0, $store_id = 0)
    {
        empty($name) && $name = Yii::$app->params['addon']['name'];
        $app_id = $merchant_id > 0 ? AppEnum::MERCHANT : AppEnum::BACKEND;

        if (empty($configModel = $this->findByName($name, $app_id, $merchant_id))) {
            $configModel = new AddonsConfig();
            $configModel->addon_name = $name;
            $configModel->merchant_id = $merchant_id;
            $configModel->store_id = $store_id;
            $configModel->app_id = $app_id;
            $configModel->data = [];
        }

        $configModel->data = array_merge($configModel->data, $config);
        $configModel->save();

        // 清空缓存
        return $this->findConfigByCache($name, $merchant_id, true);
    }
}
