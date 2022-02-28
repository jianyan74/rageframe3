<?php

namespace api\controllers;

use common\enums\MemberTypeEnum;

/**
 * 商家 api 基类
 *
 * Class MerchantUserAuthController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantUserAuthController extends UserAuthController
{
    /**
     * 用户类型
     *
     * @var int
     */
    protected $memberType = MemberTypeEnum::MERCHANT;
}
