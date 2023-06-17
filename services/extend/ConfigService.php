<?php

namespace services\extend;

use common\enums\ExtendConfigNameEnum;
use common\enums\StatusEnum;
use common\models\extend\Config;
use common\models\extend\printer\FeiE;
use common\models\extend\printer\YiLianYun;

/**
 * Class ConfigService
 * @package services\extend
 */
class ConfigService
{
    /**
     * 获取对应的配置模型
     *
     * @param $name
     * @param $data
     * @return string|\yii\base\Model|FeiE|YiLianYun
     */
    public function getModel($name, $data)
    {
        $model = ExtendConfigNameEnum::getModelValue($name);
        $model->attributes = $data;

        return $model;
    }

    /**
     * 查询该类型下面所有配置
     *
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByType($type, $merchant_id, $extend = '')
    {
        return Config::find()
            ->where(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->andFilterWhere(['extend' => $extend])
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Config
     */
    public function findById($id)
    {
        return Config::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }
}
