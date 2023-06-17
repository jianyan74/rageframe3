<?php

namespace services\extend\printer;

use Yii;
use common\enums\ExtendConfigNameEnum;
use common\enums\ExtendConfigTypeEnum;
use common\models\extend\Config;
use common\queues\ReceiptPrinterJob;

/**
 * 小票打印
 *
 * Class PrinterService
 * @package services\extend
 */
class PrinterService
{
    /**
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 打印单个
     *
     * @param int $config_id
     * @param array $data
     * @return bool|string|null
     */
    public function printerById($config_id, $data)
    {
        // 是否进入队列
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new ReceiptPrinterJob([
                'config' => Yii::$app->services->extendConfig->findById($config_id),
                'data' => $data,
            ]));

            return $messageId;
        } else {
            return $this->receiptPrinter(Yii::$app->services->extendConfig->findById($config_id), $data);
        }
    }

    /**
     * 执行打印
     *
     * @param Config $config
     * @param array $data
     * @return bool
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function receiptPrinter(Config $config, $data)
    {
        if (empty($config) || empty($data)) {
            return false;
        }

        $model = Yii::$app->services->extendConfig->getModel($config->name, $config->data);
        switch ($config->name) {
            case ExtendConfigNameEnum::YI_LIAN_YUN :
                Yii::$app->services->extendPrinterYiLianYun->text($data, '', $model);
                break;
            case ExtendConfigNameEnum::FEI_E :
                Yii::$app->services->extendPrinterFeiEYun->print($data, $model);
                break;
            case ExtendConfigNameEnum::XP_YUN :
                Yii::$app->services->extendPrinterXpYun->print($data, $model);
                break;
        }

        return true;
    }

    /**
     * 获取自动打印的配置
     *
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllAuto($merchant_id)
    {
        return Yii::$app->services->extendConfig->findByType(ExtendConfigTypeEnum::RECEIPT_PRINTER, $merchant_id);
    }
}
