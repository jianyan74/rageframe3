<?php

namespace common\forms;

use Yii;
use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\Member;

/**
 * Class MemberForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MemberForm extends Member
{
    /**
     * @var int
     */
    protected $defaultType = MemberTypeEnum::MEMBER;

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['mobile'], 'uniqueMobile'],
            [['username'], 'uniqueUsername'],
            [['email'], 'uniqueEmail'],
            [['id'], 'safe'],
        ]);
    }

    /**
     * @param $attribute
     */
    public function uniqueUsername($attribute)
    {
        $member = Yii::$app->services->member->findByCondition([
            'and',
            ['username' => $this->username],
            ['type' => $this->defaultType],
            ['>=', 'status', StatusEnum::DISABLED]
        ]);

        if (
            !empty($member) &&
            $member->id != $this->id
        ) {
            $this->addError($attribute, '该账号已存在');
        }
    }

    public function uniqueMobile($attribute)
    {
        $member = Yii::$app->services->member->findByCondition([
            'and',
            ['mobile' => $this->mobile],
            ['type' => $this->defaultType],
            ['>=', 'status', StatusEnum::DISABLED]
        ]);

        if (
            !empty($member) &&
            $member->id != $this->id
        ) {
            $this->addError($attribute, '该手机号码已存在');
        }
    }

    public function uniqueEmail($attribute)
    {
        $member = Yii::$app->services->member->findByCondition([
            'and',
            ['email' => $this->email],
            ['type' => $this->defaultType],
            ['>=', 'status', StatusEnum::DISABLED]
        ]);

        if (
            !empty($member) &&
            $member->id != $this->id
        ) {
            $this->addError($attribute, '该邮箱已存在');
        }
    }
}
