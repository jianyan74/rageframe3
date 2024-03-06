<?php

namespace services\store;

use Yii;
use common\enums\StatusEnum;
use addons\TinyStore\common\models\store\Store;

/**
 * Class StoreService
 * @package ${NAMESPACE}
 * @author jianyan74 <751393839@qq.com>
 */
class StoreService
{
    /**
     * @var int
     */
    protected $store_id = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->store_id;
    }

    /**
     * @param $store_id
     */
    public function setId($store_id)
    {
        $this->store_id = $store_id;
    }

    /**
     * @return int
     */
    public function getNotNullId(): int
    {
        return !empty($this->store_id) ? (int)$this->store_id : 0;
    }

    /**
     * 获取自动判断的商户ID
     *
     * @return int
     */
    public function getAutoId()
    {
        return 0;
    }

    /**
     * @param $store_id
     */
    public function addId($store_id)
    {
        !$this->store_id && $this->store_id = $store_id;
    }

    /**
     * @param $store
     * @return string
     */
    public function getTitle($store)
    {
        if (empty($store)) {
            return '---';
        }

        if (Yii::$app->services->devPattern->isB2C()) {
            return '平台';
        }

        return $store['title'];
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByCondition($condition)
    {
        return Store::find()
            ->where($condition)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllByCondition($condition)
    {
        return Store::find()
            ->where($condition)
            ->asArray()
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Store
     */
    public function findById($id)
    {
        return Store::find()
            ->where(['id' => $id])
            ->one();
    }
}
