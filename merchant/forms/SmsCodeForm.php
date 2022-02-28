<?php

namespace merchant\forms;

use Yii;
use yii\base\Model;
use common\helpers\RegularHelper;
use addons\Merchants\common\enums\SmsUsageEnum;

/**
 * Class SmsCodeForm
 * @package merchant\forms
 */
class SmsCodeForm extends Model
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $usage;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile', 'usage'], 'required'],
            [['mobile'], 'isBeforeSend'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'usage' => '用途',
        ];
    }

    /**
     * @param $attribute
     */
    public function isBeforeSend($attribute)
    {
        if ($this->usage == SmsUsageEnum::REGISTER && Yii::$app->services->merchant->findByCondition(['mobile' => $this->mobile])) {
            $this->addError($attribute, '该手机号码已注册');
        }

        if (
            !in_array($this->usage, [SmsUsageEnum::REGISTER]) &&
            !Yii::$app->services->merchant->findByCondition(['mobile' => $this->mobile])
        ) {
            $this->addError($attribute, '该手机号码未注册');
        }
    }

    /**
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function send()
    {
        $code = rand(1000, 9999);

        return Yii::$app->services->extendSms->send($this->mobile, $code, $this->usage);
    }
}
