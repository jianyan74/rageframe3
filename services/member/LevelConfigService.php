<?php

namespace services\member;

use common\components\Service;
use common\models\member\LevelConfig;

/**
 * Class LevelConfigService
 * @package services\member
 */
class LevelConfigService extends Service
{
    /**
     * @var array
     */
    protected $models = [];

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOne($merchant_id)
    {
        return LevelConfig::find()
            ->where(['merchant_id' => $merchant_id])
            ->one();
    }

    /**
     * @return LevelConfig
     */
    public function one($merchant_id)
    {
        if (!empty($this->models[$merchant_id])) {
            return $this->models[$merchant_id];
        }

        /* @var $model LevelConfig */
        if (empty($model = $this->findOne($merchant_id))) {
            $model = new LevelConfig();
            $model = $model->loadDefaultValues();
            $model->save();
        }

        $this->models[$merchant_id] = $model;

        return $model;
    }
}
