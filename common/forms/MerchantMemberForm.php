<?php

namespace common\forms;

use common\enums\MemberTypeEnum;

/**
 * Class MerchantMemberForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantMemberForm extends MemberForm
{
    /**
     * @var int
     */
    protected $defaultType = MemberTypeEnum::MERCHANT;
}
