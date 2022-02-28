<?php

namespace api\controllers;

use common\enums\MemberTypeEnum;

/**
 * 商家 api 基类
 *
 * Class MerchantOnAuthController
 * @package api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantOnAuthController extends OnAuthController
{
    /**
     * 用户类型
     *
     * @var int
     */
    protected $memberType = MemberTypeEnum::MERCHANT;
}
