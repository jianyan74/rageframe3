<?php

namespace services\common;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use common\enums\AppEnum;
use common\models\common\Config;
use common\models\common\ConfigValue;

/**
 * 配置管理
 *
 * Class ConfigService
 * @package services\common
 */
class ConfigService
{
    /**
     * @var array
     */
    protected $configCache = [];

    /**
     * 返回配置名称
     *
     * @param string $name 字段名称
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @param string $merchant_id
     * @return string|null
     */
    public function config($name, $noCache = false, $merchant_id = '')
    {
        $info = $this->configAll($noCache, $merchant_id);

        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 返回配置名称
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
    public function configAll($noCache = false, $merchant_id = '')
    {
        $merchant_id = 0;
        $app_id = AppEnum::BACKEND;

        $info = $this->baseConfigAll($app_id, $merchant_id, $noCache);

        return !empty($info) ? $info : [];
    }

    /**
     * 返回总后台配置名称
     *
     * @param string $name 字段名称
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @param string $merchant_id
     * @return string|null
     */
    public function backendConfig($name, $noCache = false)
    {
        // 获取缓存信息
        $info = $this->baseConfigAll(AppEnum::BACKEND, 0, $noCache);

        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 返回总后台配置
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
    public function backendConfigAll($noCache = false)
    {
        $info = $this->baseConfigAll(AppEnum::BACKEND, 0, $noCache);

        return !empty($info) ? $info : [];
    }

    /**
     * 获取当前商户配置
     *
     * @param $name
     * @param bool $noCache
     * @return string|null
     */
    public function merchantConfig($name, $noCache = false, $merchant_id = '')
    {
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();
        !$merchant_id && $merchant_id = 1;

        // 获取缓存信息
        $info = $this->baseConfigAll(AppEnum::MERCHANT, $merchant_id, $noCache);

        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 获取当前商户的全部配置
     *
     * @param bool $noCache
     * @return array|bool|mixed
     */
    public function merchantConfigAll($noCache = false, $merchant_id = '')
    {
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();
        !$merchant_id && $merchant_id = 1;

        $info = $this->baseConfigAll(AppEnum::MERCHANT, $merchant_id, $noCache);

        return !empty($info) ? $info : [];
    }

    /**
     * @param string $app_id
     * @param int $merchant_id
     * @param bool $noCache
     * @return array|mixed
     */
    protected function baseConfigAll($app_id, $merchant_id, $noCache)
    {
        $cacheKey = 'config:' . $app_id . ':' . $merchant_id;
        if ($noCache == false && !empty($this->config[$cacheKey])) {
            return $this->configCache[$cacheKey];
        }

        if ($noCache == true || !($this->configCache[$cacheKey] = Yii::$app->cache->get($cacheKey))) {
            $this->configCache[$cacheKey] = [];

            $config = $this->findByNames($app_id, $merchant_id);
            foreach ($config as $row) {
                $this->configCache[$cacheKey][$row['name']] = $row['value']['data'] ?? $row['default_value'];
            }

            Yii::$app->cache->set($cacheKey, $this->configCache[$cacheKey], 60);
        }

        return $this->configCache[$cacheKey];
    }

    /**
     * @param string $app_id
     * @param int $merchant_id
     * @param array $data
     * @return array|bool|mixed
     */
    public function updateAll(string $app_id, int $merchant_id, array $data)
    {
        $config = $this->findByNames($app_id, $merchant_id, array_keys($data));
        /** @var Config $item */
        foreach ($config as $item) {
            $val = $data[$item['name']] ?? '';
            /** @var ConfigValue $model */
            $model = $item->value ?? new ConfigValue();
            $model->merchant_id = $merchant_id;
            $model->config_id = $item->id;
            $model->app_id = $item->app_id;
            $model->data = is_array($val) ? Json::encode($val) : trim($val);
            $model->save();
        }

        if ($app_id === AppEnum::BACKEND) {
            return $this->backendConfigAll(true);
        }

        return $this->merchantConfigAll(true, $merchant_id);
    }

    /**
     * @param $name
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findSaveByName($name, $app_id, $data = [])
    {
        $config = $this->findByName($name, $app_id);
        if (empty($config)) {
            $model = new Config();
            $model = $model->loadDefaultValues();
            $model->attributes = $data;
            $model->save();
        }
    }

    /**
     * @param $name
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByName($name, $app_id)
    {
        return Config::find()
            ->where(['name' => $name, 'app_id' => $app_id])
            ->one();
    }

    /**
     * @param $app_id
     * @param $merchant_id
     * @param $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByNames($app_id, $merchant_id, $names = [])
    {
        return Config::find()
            ->filterWhere(['in', 'name', $names])
            ->andWhere(['app_id' => $app_id])
            ->with([
                'value' => function (ActiveQuery $query) use ($merchant_id, $app_id) {
                    return $query->andWhere(['app_id' => $app_id, 'merchant_id' => $merchant_id]);
                }
            ])
            ->all();
    }
}
