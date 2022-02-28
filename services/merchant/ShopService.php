<?php

namespace services\merchant;

use Yii;
use common\enums\StatusEnum;
use common\models\merchant\Shop;

/**
 * Class ShopService
 * @package services\merchant
 */
class ShopService
{
    /**
     * @var int
     */
    protected $shop_id = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->shop_id;
    }

    /**
     * @param $shop_id
     */
    public function setId($shop_id)
    {
        $this->shop_id = $shop_id;
    }

    /**
     * @return int
     */
    public function getNotNullId(): int
    {
        return !empty($this->shop_id) ? (int)$this->shop_id : 0;
    }

    /**
     * 获取自动判断的商户ID
     *
     * @return int
     */
    public function getAutoId()
    {
        return Yii::$app->services->devPattern->isSAAS() ? $this->getNotNullId() : 0;
    }

    /**
     * @param $shop_id
     */
    public function addId($shop_id)
    {
        !$this->shop_id && $this->shop_id = $shop_id;
    }

    /**
     * @param $shop
     * @return string
     */
    public function getTitle($shop)
    {
        if (empty($shop)) {
            return '---';
        }

        if (Yii::$app->services->devPattern->isB2C()) {
            return '平台';
        }

        return $shop['title'];
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByCondition($condition)
    {
        return Shop::find()
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
        return Shop::find()
            ->where($condition)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Shop
     */
    public function findById($id)
    {
        return Shop::find()
            ->where(['id' => $id])
            ->one();
    }
}