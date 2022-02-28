<?php

namespace api\modules\v1\forms;

use Yii;
use yii\base\Model;
use common\enums\MemberTypeEnum;
use common\helpers\RegularHelper;
use common\enums\SmsUsageEnum;
use common\enums\AccessTokenGroupEnum;

/**
 * Class MobileLogin
 * @package api\modules\v1\models
 * @author jianyan74 <751393839@qq.com>
 */
class MobileLogin extends Model
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $group;

    /**
     * @var
     */
    protected $_user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile', 'code', 'group'], 'required'],
            ['code', 'filter', 'filter' => 'trim'],
            ['code', '\common\models\validators\SmsCodeValidator', 'usage' => SmsUsageEnum::LOGIN],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            ['mobile', 'validateMobile'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'code' => '验证码',
            'group' => '组别',
        ];
    }

    /**
     * @param $attribute
     */
    public function validateMobile($attribute)
    {
        if (!$this->getUser()) {
            $this->addError($attribute, '找不到用户');
        }
    }

    /**
     * 获取用户信息
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            $this->_user = Yii::$app->services->member->findByCondition([
                'mobile' => $this->mobile,
                'type' => MemberTypeEnum::MEMBER
            ]);
        }

        return $this->_user;
    }
}
