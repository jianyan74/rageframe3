<?php

namespace services\oauth2;

use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\StringHelper;
use common\models\oauth2\Client;

/**
 * Class ClientService
 * @package services\oauth2
 * @author jianyan74 <751393839@qq.com>
 */
class ClientService extends Service
{
    /**
     * @param $client_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByClientId($client_id)
    {
        return Client::find()
            ->where(['client_id' => $client_id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $merchant_id
     * @param $title
     */
    public function createByMerchantId($merchant_id, $title)
    {
        $model = new Client();
        $model = $model->loadDefaultValues();
        $model->merchant_id = $merchant_id;
        $model->title = $title;
        $model->client_id = StringHelper::random(15);
        $model->client_secret = StringHelper::random(30);
        $model->scope = [];
        if ($this->findByClientId($model->client_id)){
            return $this->createByMerchantId($merchant_id, $title);
        }

        $model->save();

        return $model;
    }

    /**
     * @param $merchant_id
     * @param $title
     */
    public function resetByMerchantId($merchant_id, $title)
    {
        $model = $this->findByMerchantId($merchant_id);
        $model->title = $title;
        $model->client_id = StringHelper::random(15);
        $model->client_secret = StringHelper::random(30);
        if ($this->findByClientId($model->client_id)){
            return $this->resetByMerchantId($merchant_id, $title);
        }

        $model->save();

        return $model;
    }

    /**
     * @return array|\yii\db\ActiveRecord|null|Client
     */
    public function findByMerchantId($merchant_id)
    {
        return Client::find()
            ->where(['merchant_id' => $merchant_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }
}
