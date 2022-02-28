<?php

namespace merchant\forms;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\enums\AuditStatusEnum;
use common\enums\MemberTypeEnum;
use common\enums\AppEnum;
use common\enums\OperatingTypeEnum;
use addons\Merchants\common\enums\SmsUsageEnum;
use addons\Merchants\common\models\Merchant;
use addons\Merchants\common\models\SettingForm;

/**
 * Class SignUpForm
 * @package merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class SignUpForm extends Model
{
    public $id;
    public $title;
    public $username;
    public $cate_id;
    public $mobile;
    public $auth_role_id;
    public $password;
    public $re_pass;
    public $rememberMe;

    protected $member;
    protected $merchant;

    public $code;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['cate_id'], 'integer'],
            [['rememberMe'], 'isRequired'],
            [['title', 'auth_role_id', 'cate_id', 'username', 'mobile', 'password', 'code', 're_pass'], 'required'],
            ['mobile', 'string', 'max' => 15],
            [
                ['title'],
                'unique',
                'targetClass' => Merchant::class,
                'message' => '{attribute}已经被占用.',
                'filter' => function (ActiveQuery $query) {
                    return $query->andWhere(['>=', 'status', StatusEnum::DISABLED]);
                },
            ],
            [
                'mobile',
                'unique',
                'targetClass' => Member::class,
                'filter' => function (ActiveQuery $query) {
                    return $query
                        ->andWhere(['>=', 'status', StatusEnum::DISABLED])
                        ->andWhere(['type' => MemberTypeEnum::MERCHANT]);
                },
                'message' => '该手机号码已经被占用.'
            ],
            ['mobile', 'match', 'pattern' => '/^1[3456789]\d{9}$/', 'message' => '手机号码格式不正确'],
            [
                ['username'],
                'unique',
                'targetClass' => Member::class,
                'filter' => function (ActiveQuery $query) {
                    return $query
                        ->andWhere(['>=', 'status', StatusEnum::DISABLED])
                        ->andWhere(['type' => MemberTypeEnum::MERCHANT]);
                },
                'message' => '该用户名已经被占用了.'
            ],
            [
                'username',
                'match',
                'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u',
                'message' => '用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。',
            ],
            ['username', 'string', 'min' => 6, 'max' => 20],
            [['password', 're_pass'], 'string', 'min' => 6, 'max' => 20],
            ['re_pass', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致'],
            ['code', '\common\models\validators\SmsCodeValidator', 'usage' => SmsUsageEnum::REGISTER],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => '商家名称',
            'cate_id' => '主营行业',
            'auth_role_id' => '开店套餐',
            'code' => '验证码',
            'username' => '商家账户',
            'mobile' => '手机号码',
            'password' => '账户密码',
            're_pass' => '确认密码',
            'rememberMe' => '',
        ];
    }

    /**
     * @param $attribute
     */
    public function isRequired($attribute)
    {
        if (empty($this->rememberMe)) {
            $this->addError($attribute, '请同意商家入驻协议');
        }
    }

    /**
     * @return bool|Merchant
     */
    public function register()
    {
        /** @var SettingForm $setting */
        $setting = Yii::$app->merchantsService->config->setting();
        // 事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $merchant = new Merchant();
            $merchant = $merchant->loadDefaultValues();
            $merchant->title = $this->title;
            $merchant->cate_id = $this->cate_id;
            $merchant->mobile = $this->mobile;
            $merchant->status = StatusEnum::ENABLED;
            $merchant->audit_status = AuditStatusEnum::DISABLED;
            $merchant->auth_role_id = $this->auth_role_id;
            $merchant->end_time = time() + 3600 * 24 * $setting->register_experience_day;
            $merchant->operating_type = OperatingTypeEnum::ENTER;
            // 自动通过审核
            if ($setting->register_auto_pass == StatusEnum::ENABLED) {
                $merchant->audit_status = AuditStatusEnum::ENABLED;
            }

            if (!$merchant->save()) {
                $this->addErrors($merchant->getErrors());
                throw new NotFoundHttpException('商户信息编辑错误');
            }

            $member = new Member();
            $member->merchant_id = $merchant->id;
            $member->username = $this->username;
            $member->mobile = $this->mobile;
            $member->type = MemberTypeEnum::MERCHANT;
            $member->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            if (!$member->save()) {
                $this->addErrors($member->getErrors());
                throw new NotFoundHttpException('用户信息编辑错误');
            }

            // 角色授权
            Yii::$app->services->rbacAuthAssignment->assign([$this->auth_role_id], $member->id, AppEnum::MERCHANT);

            $transaction->commit();

            return $merchant;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
