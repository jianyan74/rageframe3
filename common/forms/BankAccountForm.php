<?php

namespace common\forms;

use common\enums\AccountTypeEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\BankAccount;

/**
 * Class BankAccountForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountForm extends BankAccount
{
    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['account_type', 'verifyAccountType']
        ]);
    }

    /**
     * @param $attribute
     */
    public function verifyAccountType($attribute)
    {
        if ($this->status == StatusEnum::DELETE) {
            return;
        }

        if ($this->account_type == AccountTypeEnum::UNION) {
            !$this->account_number && $this->addError($attribute, '请填写银行账号');
            !$this->bank_name && $this->addError($attribute, '请填写支行信息');
        }

        if ($this->account_type == AccountTypeEnum::ALI) {
            !$this->account_number && $this->addError($attribute, '请填写支付宝账号');
        }
    }
}
