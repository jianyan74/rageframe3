<?php

namespace api\modules\v1\forms;

use yii\base\Model;
use yii\db\ActiveQuery;
use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\helpers\RegularHelper;
use common\models\member\Member;
use common\enums\SmsUsageEnum;
use common\enums\AccessTokenGroupEnum;
use common\models\validators\SmsCodeValidator;

/**
 * Class RegisterForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class RegisterForm extends Model
{
    public $mobile;
    public $password;
    public $password_repetition;
    public $code;
    public $group;
    public $nickname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'group', 'code', 'password', 'password_repetition', 'nickname'], 'required'],
            [['nickname'], 'string'],
            [['password'], 'string', 'min' => 6],
            [
                ['mobile'],
                'unique',
                'targetClass' => Member::class,
                'targetAttribute' => 'mobile',
                'filter' => function (ActiveQuery $query) {
                    return $query
                        ->andWhere(['type' => MemberTypeEnum::MEMBER])
                        ->andWhere(['>=', 'status', StatusEnum::DISABLED]);
                },
                'message' => '此{attribute}已存在。'
            ],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号码'],
            [['password_repetition'], 'compare', 'compareAttribute' => 'password'],// 验证新密码和重复密码是否相等
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()],
            ['code', SmsCodeValidator::class, 'usage' => SmsUsageEnum::REGISTER],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'nickname' => '昵称',
            'password' => '密码',
            'password_repetition' => '重复密码',
            'group' => '类型',
            'code' => '验证码',
        ];
    }
}
