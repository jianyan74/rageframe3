<?php

namespace common\forms;

use common\enums\MemberTypeEnum;

/**
 * Class ManagerForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerMemberForm extends MemberForm
{
    /**
     * @var int
     */
    protected $defaultType = MemberTypeEnum::MANAGER;
}
