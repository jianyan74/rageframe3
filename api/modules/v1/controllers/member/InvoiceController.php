<?php

namespace api\modules\v1\controllers\member;

use common\models\member\Invoice;
use api\controllers\UserAuthController;

/**
 * 发票管理
 *
 * Class InvoiceController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceController extends UserAuthController
{
    /**
     * @var Invoice
     */
    public $modelClass = Invoice::class;
}
