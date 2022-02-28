<?php

namespace api\modules\v1\controllers\member;

use common\models\member\Address;
use api\controllers\UserAuthController;

/**
 * 收货地址
 *
 * Class AddressController
 * @package api\modules\v1\controllers\member
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class AddressController extends UserAuthController
{
    /**
     * @var Address
     */
    public $modelClass = Address::class;
}
