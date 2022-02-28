<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;
use common\models\extend\Config;

/**
 * 小票打印
 *
 * Class ReceiptPrinterJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class ReceiptPrinterJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->services->extendPrinter->receiptPrinter($this->config, $this->data);
    }
}