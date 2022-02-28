<?php

namespace addons\Member\merchant\forms;

use common\forms\MemberForm;
use common\helpers\ArrayHelper;

/**
 * Class MemberCreateForm
 * @package addons\Member\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MemberCreateForm extends MemberForm
{
    /**
     * @return array|array[]
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['username', 'required'],
            ['password_hash', 'required'],
        ]);
    }
}
