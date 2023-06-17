<?php

namespace services\merchant;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\merchant\Merchant;

/**
 * 商户
 *
 * Class MerchantService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantService extends Service
{
    /**
     * @var int
     */
    protected $merchant_id = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->merchant_id;
    }

    /**
     * @param $merchant_id
     */
    public function setId($merchant_id)
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * @return int
     */
    public function getNotNullId(): int
    {
        return !empty($this->merchant_id) ? (int)$this->merchant_id : 0;
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
     * @param $merchant_id
     */
    public function addId($merchant_id)
    {
        !$this->merchant_id && $this->merchant_id = $merchant_id;
    }

    /**
     * @param $merchant
     * @return string
     */
    public function getTitle($merchant)
    {
        if (empty($merchant)) {
            return '---';
        }

        if (Yii::$app->services->devPattern->isB2C()) {
            return '平台';
        }

        return $merchant['title'];
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByCondition($condition)
    {
        return Merchant::find()
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
        return Merchant::find()
            ->where($condition)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findBaseById($id)
    {
        return Merchant::find()
            ->select([
                'id',
                'title',
                'cover',
                'address_name',
                'address_details',
                'longitude',
                'latitude',
                'collect_num',
            ])
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->one();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Merchant
     */
    public function findById($id)
    {
        return Merchant::find()
            ->where(['id' => $id])
            ->one();
    }
}
