<?php

namespace backend\modules\extend\controllers;

use common\enums\ExtendConfigTypeEnum;
use common\traits\ExtendConfigTrait;
use backend\controllers\BaseController;

/**
 * Class HardwareReceiptPrinterController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReceiptPrinterController extends BaseController
{
    use ExtendConfigTrait;

    /**
     * @var string 类型
     */
    public $type = ExtendConfigTypeEnum::RECEIPT_PRINTER;
}
