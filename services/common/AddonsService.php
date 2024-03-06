<?php

namespace services\common;

use Yii;
use yii\helpers\Console;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\AddonHelper;
use common\models\common\Addons;
use common\components\BaseAddonConfig;
use common\components\Service;
use common\helpers\StringHelper;
use common\enums\AppEnum;
use common\enums\OfficialEnum;
use common\interfaces\AddonWidget;
use Overtrue\Pinyin\Pinyin;

/**
 * Class AddonsService
 * @package services\common
 */
class AddonsService extends Service
{
    /**
     * 初始化模块信息
     *
     * @param $name
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function initParams($name)
    {
        if (!$name) {
            throw new NotFoundHttpException("插件不能为空");
        }

        if (!($addon = Yii::$app->services->addons->findCacheByName($name, Yii::$app->id == AppEnum::BACKEND))) {
            throw new NotFoundHttpException("插件不存在");
        }

        Yii::$app->params['addon'] = $addon;
        Yii::$app->params['addonName'] = StringHelper::toUnderScore(Yii::$app->params['addon']['name']);
        Yii::$app->params['inAddon'] = true;

        return $addon;
    }

    /**
     * 更新配置
     *
     * @param $name
     * @param BaseAddonConfig $config
     * @param $default_config
     * @return array|Addons|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function updateByName($name, $config, $default_config)
    {
        if (!($model = $this->findByName($name))) {
            $model = new Addons();
            $model = $model->loadDefaultValues();
        }

        $model->attributes = $config->info;
        $model->is_merchant_route_map = $config->isMerchantRouteMap ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->group = $config->group;
        $model->bootstrap = $config->bootstrap ?? '';
        $model->service = $config->service ?? '';
        $model->default_config = $default_config;
        $model->console = $config->console ?? [];
        $model->updated_at = time();
        // 首先字母转大写拼音
        if (($chinese = StringHelper::strToChineseCharacters($model->title)) && !empty($chinese[0])) {
            $title_initial = mb_substr($chinese[0][0], 0, 1, 'utf-8');
            $model->title_initial = ucwords((new Pinyin())->abbr($title_initial));
        }

        !$model->save() && $this->error($model);

        // 更新缓存
        Yii::$app->services->addons->updateCacheByName($name);

        return $model;
    }

    /**
     * 升级数据库
     *
     * @param $name
     * @return bool
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function upgradeSql($name)
    {
        if (
            !($class = AddonHelper::getConfigClass($name)) ||
            !($model = $this->findByName($name))
        ) {
            throw new NotFoundHttpException('实例化失败,插件不存在或检查插件名称');
        }

        // 更新数据库
        $upgradeClass = AddonHelper::getAddonRoot($name) . (new $class)->upgrade;
        if (!class_exists($upgradeClass)) {
            throw new NotFoundHttpException($upgradeClass . '未找到');
        }

        /** @var AddonWidget $upgradeModel */
        $upgradeModel = new $upgradeClass;
        if (!method_exists($upgradeModel, 'run')) {
            throw new NotFoundHttpException($upgradeClass . '/run方法未找到');
        }

        if (!isset($upgradeModel->versions)) {
            throw new NotFoundHttpException($upgradeClass . '下 versions 属性未找到');
        }

        $versions = $upgradeModel->versions;
        $count = count($versions);
        for ($i = 0; $i < $count; $i++) {
            // 验证版本号和更新
            if ($model->version == $versions[$i] && isset($versions[$i + 1])) {
                // 开启事务
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model->version = $versions[$i + 1];
                    $upgradeModel->run($model);
                    $model->save();

                    // 输出日志
                    Yii::$app->id == AppEnum::CONSOLE && Console::output('updating...' . $model->version . '...' . date('Y-m-d H:i:s'));

                    // 完成事务
                    $transaction->commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    $transaction->rollBack();

                    throw new UnprocessableEntityHttpException($e->getMessage());
                }
            }
        }

        return true;
    }

    /**
     * 获取本地插件列表
     *
     * @return array
     */
    public function getLocalList()
    {
        $addonDir = Yii::getAlias('@addons');
        // 获取插件列表
        $dirs = array_map('basename', glob($addonDir . '/*'));
        $list = ArrayHelper::toArray($this->findByNames($dirs, ['*']));
        $tmpAddons = ArrayHelper::arrayKey($list, 'name');
        $addons = [];
        foreach ($dirs as $value) {
            // 判断是否安装
            if (!isset($tmpAddons[$value])) {
                // 实例化插件失败忽略执行
                if (class_exists($class = AddonHelper::getAddonConfig($value))) {
                    $config = new $class;
                    $addons[$value] = $config->info;
                }
            }
        }

        return $addons;
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findCacheByName($name, $noCache = false)
    {
        $cacheKey = 'addon'. ':' . Yii::$app->id . ':' . $name;
        if (!$noCache && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $data = $this->findByName($name);
        if (Yii::$app->id == AppEnum::CONSOLE) {
            return $data;
        }

        Yii::$app->cache->set($cacheKey, $data, 360);

        return $data;
    }

    /**
     * 获取插件名称列表
     *
     * @param bool $noCache
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function findCacheAllNames($noCache = false)
    {
        if (Yii::$app->id == AppEnum::CONSOLE) {
            return $this->findAllInInit();
        }

        $cacheKey = 'addonsName';
        if (!$noCache && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $models = $this->findAllInInit();
        Yii::$app->cache->set($cacheKey, $models, 360);

        return $models;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function findAllInInit()
    {
        return Addons::find()
            ->select(['name', 'is_merchant_route_map', 'service', 'updated_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * 获取列表
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Addons::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord|Addons
     */
    public function findByName($name)
    {
        return Addons::find()
            ->where(['name' => $name, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param array $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByNames(array $names, $select = ['id', 'name', 'title'])
    {
        return Addons::find()
            ->select($select)
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['in', 'name', $names])
            ->all();
    }

    /**
     * @param array $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findBrief()
    {
        return Addons::find()
            ->select(['title', 'name', 'group'])
            ->asArray()
            ->all();
    }

    /**
     * @param $name
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findAuthority()
    {
        return Addons::find()
            ->where(['name' => OfficialEnum::AUTHORITY, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * 触发更新缓存
     *
     * @param $name
     */
    public function updateCacheByName($name)
    {
        $this->findCacheAllNames(true);
        $this->findCacheByName($name, true);
    }
}
