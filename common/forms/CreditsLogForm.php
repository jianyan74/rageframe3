<?php

namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\member\Member;
use common\models\member\Level;
use common\models\member\Account;

/**
 * Class CreditsLogForm
 * @package common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogForm extends Model
{
    /**
     * @var Member
     */
    public $member;
    /**
     * @var Account
     */
    public $account;
    public $num = 0;
    public $group;
    public $remark = '';
    public $map_id = 0;

    /**
     * 是否消费
     *
     * @var bool
     */
    public $is_consume = false;

    /**
     * 是否累计
     *
     * @var bool
     */
    public $is_accumulate = true;

    /**
     * 是否赠送
     *
     * @var bool
     */
    public $is_give = false;

    /**
     * 更新会员等级
     *
     * @var bool
     */
    public $update_level = true;

    /**
     * 支付类型
     *
     * @var int
     */
    public $pay_type = 0;

    /**
     * 字段类型(请不要占用)
     *
     * @var string
     */
    public $type;

    /**
     * 更新级别
     *
     * @param float $consume_money 累计消费金额
     * @param int $accumulate_integral 累计积分
     * @param int $accumulate_growth 累计成长值
     * @return false
     */
    public function updateLevel(float $consume_money, int $accumulate_integral, int $accumulate_growth)
    {
        if (empty($this->member)) {
            return false;
        }

        /** @var Level $level */
        $level = Yii::$app->services->memberLevel->getLevel(
            (int)$this->member->current_level,
            (int)$this->member->level_expiration_time,
            $consume_money,
            $accumulate_integral,
            $accumulate_growth
        );

        $level != false && Member::updateAll(['current_level' => $level->level], ['id' => $this->member->id]);
    }
}
