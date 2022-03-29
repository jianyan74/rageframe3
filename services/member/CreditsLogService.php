<?php

namespace services\member;

use Yii;
use yii\web\NotFoundHttpException;
use common\forms\CreditsLogForm;
use common\components\Service;
use common\models\member\CreditsLog;
use common\enums\CreditsLogTypeEnum;
use common\models\member\Account;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\helpers\EchantsHelper;

/**
 * Class CreditsLogService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogService extends Service
{
    /**
     * 增加积分
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function incrInt(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_INTEGRAL;

        return $this->userInt($creditsLogForm);
    }

    /**
     * 减少积分
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function decrInt(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = -abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_INTEGRAL;

        return $this->userInt($creditsLogForm);
    }

    /**
     * 增加余额
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function incrMoney(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_MONEY;

        return $this->userMoney($creditsLogForm);
    }

    /**
     * 减少余额
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function decrMoney(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = -abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_MONEY;

        return $this->userMoney($creditsLogForm);
    }

    /**
     * 消费
     *
     * 一般用于微信/支付宝/银联消费记录使用
     *
     * @param CreditsLogForm $creditsLogForm
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    public function consumeMoney(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::CONSUME_MONEY;

        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account ?? '';
        }

        if (empty($account)) {
            return $this->create($creditsLogForm, 0, 0);
        }

        // 直接记录日志不修改
        if (empty($creditsLogForm->member) || $creditsLogForm->num == 0) {
            return $this->create($creditsLogForm, $account->consume_money, $account->consume_money);
        }

        // 消费
        if (!Account::updateAllCounters(['consume_money' => $creditsLogForm->num], ['id' => $account->id])) {
            throw new NotFoundHttpException('消费失败');
        }

        // 变动级别
        $creditsLogForm->update_level && $creditsLogForm->updateLevel(
            $account->consume_money + $creditsLogForm->num,
            $account->accumulate_integral,
            $account->accumulate_growth
        );

        // 记录日志
        return $this->create($creditsLogForm, $account->consume_money, $account->consume_money + $creditsLogForm->num);
    }

    /**
     * 提现完成
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function withdrawAccomplish(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account ?? '';
        }

        // 增加提现金额
        if (!$account->updateAllCounters([
            'accumulate_drawn_money' => $creditsLogForm->num,
        ], ['id' => $account->id])) {
            throw new NotFoundHttpException('提现失败');
        }
    }

    /**
     * 增加成长值
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function incrGrowth(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_GROWTH;

        return $this->userGrowth($creditsLogForm);
    }

    /**
     * 减少成长值
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function decrGrowth(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = -abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::USER_GROWTH;

        return $this->userGrowth($creditsLogForm);
    }

    /**
     * 增加节省
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function incrEconomizeMoney(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::ECONOMIZE;

        return $this->userEconomizeMoney($creditsLogForm);
    }

    /**
     * 减少节省
     *
     * @param CreditsLogForm $creditsLogForm
     * @return bool|CreditsLog
     * @throws NotFoundHttpException
     */
    public function decrEconomizeMoney(CreditsLogForm $creditsLogForm)
    {
        $creditsLogForm->num = -abs($creditsLogForm->num);
        $creditsLogForm->type = CreditsLogTypeEnum::ECONOMIZE;

        return $this->userEconomizeMoney($creditsLogForm);
    }

    /**
     * 积分变动
     *
     * @param CreditsLogForm $creditsLogForm
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    protected function userInt(CreditsLogForm $creditsLogForm)
    {
        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account;
        }

        // 直接记录日志不修改
        if ($creditsLogForm->num == 0) {
            return $this->create($creditsLogForm, $account->user_integral, $account->user_integral);
        }

        if ($creditsLogForm->num > 0) {
            // 消费
            $counters = [
                'user_integral' => $creditsLogForm->num,
                'accumulate_integral' => $creditsLogForm->num,
            ];
            // 增加积分赠送
            $creditsLogForm->is_give && $counters['give_integral'] = $creditsLogForm->num;
            // 减少消费数量
            $creditsLogForm->is_consume && $counters['consume_integral'] = -$creditsLogForm->num;
            // 增加
            $status = Account::updateAllCounters($counters, ['id' => $account->id]);

            // 变动级别
            $creditsLogForm->update_level && $creditsLogForm->updateLevel(
                $account->consume_money,
                $account->accumulate_integral + $creditsLogForm->num,
                $account->accumulate_growth
            );
        } else {
            // 消费
            $counters = ['user_integral' => $creditsLogForm->num];
            // 减少积分赠送
            $creditsLogForm->is_give && $counters['give_integral'] = $creditsLogForm->num;
            // 增加消费数量
            $creditsLogForm->is_consume && $counters['consume_integral'] = abs($creditsLogForm->num);

            $status = Account::updateAllCounters($counters,
                [
                    'and',
                    ['id' => $account->id],
                    ['>=', 'user_integral', abs($creditsLogForm->num)],
                ]);
        }

        if ($status == false && $creditsLogForm->num < 0) {
            throw new NotFoundHttpException('积分不足');
        }

        if ($status == false && $creditsLogForm->num > 0) {
            throw new NotFoundHttpException('增加积分失败');
        }

        // 记录日志
        return $this->create($creditsLogForm, $account->user_integral, $account->user_integral + $creditsLogForm->num);
    }

    /**
     * 余额变动
     *
     * @param CreditsLogForm $creditsLogForm
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    protected function userMoney(CreditsLogForm $creditsLogForm)
    {
        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account;
        }

        // 直接记录日志不修改
        if ($creditsLogForm->num == 0) {
            return $this->create($creditsLogForm, $account->user_money, $account->user_money);
        }

        if ($creditsLogForm->num > 0) {
            $counters = ['user_money' => $creditsLogForm->num];
            // 增加累计
            $creditsLogForm->is_accumulate && $counters['accumulate_money'] = $creditsLogForm->num;
            // 增加金额赠送
            $creditsLogForm->is_give && $counters['give_money'] = $creditsLogForm->num;
            // 去掉消费
            $creditsLogForm->is_consume && $counters['consume_money'] = -abs($creditsLogForm->num);
            // 增加
            $status = Account::updateAllCounters($counters, ['id' => $account->id]);
        } else {
            // 消费
            $counters = ['user_money' => $creditsLogForm->num];
            // 减少积分赠送
            $creditsLogForm->is_give && $counters['give_money'] = $creditsLogForm->num;
            // 增加消费数量
            $creditsLogForm->is_consume && $counters['consume_money'] = abs($creditsLogForm->num);

            $status = Account::updateAllCounters(
                $counters,
                [
                    'and',
                    ['id' => $account->id],
                    ['>=', 'user_money', abs($creditsLogForm->num)],
                ]);

            // 变动级别
            $creditsLogForm->update_level && $creditsLogForm->updateLevel(
                $account->consume_money + abs($creditsLogForm->num),
                $account->accumulate_integral,
                $account->accumulate_growth
            );
        }

        if ($status == false && $creditsLogForm->num < 0) {
            throw new NotFoundHttpException('余额不足');
        }

        if ($status == false && $creditsLogForm->num > 0) {
            throw new NotFoundHttpException('增加余额失败');
        }

        // 记录日志
        return $this->create($creditsLogForm, $account->user_money, $account->user_money + $creditsLogForm->num);
    }

    /**
     * 成长值变动
     *
     * @param CreditsLogForm $creditsLogForm
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    protected function userGrowth(CreditsLogForm $creditsLogForm)
    {
        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account;
        }

        // 直接记录日志不修改
        if ($creditsLogForm->num == 0) {
            return $this->create($creditsLogForm, $account->user_growth, $account->user_growth);
        }

        if ($creditsLogForm->num > 0) {
            // 增加
            $status = Account::updateAllCounters([
                'user_growth' => $creditsLogForm->num,
                'accumulate_growth' => $creditsLogForm->num,
            ], ['id' => $account->id]);

            // 变动级别
            $creditsLogForm->update_level && $creditsLogForm->updateLevel(
                $account->consume_growth,
                $account->accumulate_integral,
                $account->accumulate_growth + $creditsLogForm->num
            );
        } else {
            // 消费
            $status = Account::updateAllCounters([
                'user_growth' => $creditsLogForm->num,
                'consume_growth' => abs($creditsLogForm->num)
            ],
                [
                    'and',
                    ['id' => $account->id],
                    ['>=', 'user_growth', abs($creditsLogForm->num)],
                ]);
        }

        if ($status == false && $creditsLogForm->num < 0) {
            throw new NotFoundHttpException('成长值不足');
        }

        if ($status == false && $creditsLogForm->num > 0) {
            throw new NotFoundHttpException('增加成长值失败');
        }

        // 记录日志
        return $this->create($creditsLogForm, $account->user_growth, $account->user_growth + $creditsLogForm->num);
    }

    /**
     * 节省变动
     *
     * @param CreditsLogForm $creditsLogForm
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    protected function userEconomizeMoney(CreditsLogForm $creditsLogForm)
    {
        /** @var Account $account */
        if (empty($account = $creditsLogForm->account)) {
            $account = $creditsLogForm->member->account;
        }

        // 直接记录日志不修改
        if ($creditsLogForm->num == 0) {
            return $this->create($creditsLogForm, $account->user_growth, $account->user_growth);
        }

        if ($creditsLogForm->num > 0) {
            // 增加
            $status = Account::updateAllCounters(['economize_money' => $creditsLogForm->num], ['id' => $account->id]);
        } else {
            // 消费
            $status = Account::updateAllCounters(['economize_money' => $creditsLogForm->num],
                [
                    'and',
                    ['id' => $account->id],
                    ['>=', 'economize_money', abs($creditsLogForm->num)],
                ]);
        }

        if ($status == false && $creditsLogForm->num < 0) {
            throw new NotFoundHttpException('节省不足');
        }

        if ($status == false && $creditsLogForm->num > 0) {
            throw new NotFoundHttpException('增加节省失败');
        }

        // 记录日志
        return $this->create($creditsLogForm, $account->economize_money,
            $account->economize_money + $creditsLogForm->num);
    }

    /**
     * @param CreditsLogForm $creditsLogForm
     * @param $oldNum
     * @param $newNum
     * @return CreditsLog
     * @throws NotFoundHttpException
     */
    protected function create(CreditsLogForm $creditsLogForm, $oldNum, $newNum)
    {
        $model = new CreditsLog();
        $model = $model->loadDefaultValues();
        $model->ip = Yii::$app->services->base->getUserIp();
        $model->pay_type = $creditsLogForm->pay_type;
        $model->old_num = $oldNum;
        $model->new_num = $newNum;
        $model->num = $creditsLogForm->num;
        $model->type = $creditsLogForm->type;
        $model->group = $creditsLogForm->group;
        $model->remark = $creditsLogForm->remark;
        $model->map_id = $creditsLogForm->map_id;
        if ($creditsLogForm->account) {
            $model->merchant_id = $creditsLogForm->account->merchant_id ?? 0;
            $model->member_id = $creditsLogForm->account->member_id ?? 0;
            $model->member_type = $creditsLogForm->account->member_type ?? 0;
        } else {
            $model->merchant_id = $creditsLogForm->member->merchant_id ?? 0;
            $model->member_id = $creditsLogForm->member->id ?? 0;
        }

        if (!$model->save()) {
            throw new NotFoundHttpException($this->getError($model));
        }

        return $model;
    }

    /**
     * 获取区间充值
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getRechargeStat($type, $title = '充值统计')
    {
        $fields = [
            'price' => $title,
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            $data = CreditsLog::find()
                ->select(['sum(num) as price', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['type' => CreditsLogTypeEnum::USER_MONEY])
                ->andWhere(['member_type' => MemberTypeEnum::MEMBER])
                ->andWhere(['in', 'group', ['manager', 'recharge']])
                ->andWhere(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();

            foreach ($data as &$datum) {
                $datum['price'] = abs($datum['price']);
            }

            return $data;
        }, $fields, $time, $format);
    }

    /**
     * 获取区间消费统计
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type, $credit_type = CreditsLogTypeEnum::CONSUME_MONEY, $title = '第三方消费统计')
    {
        $fields = [
            'price' => $title,
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) use ($credit_type) {
            $data = CreditsLog::find()
                ->select(['sum(num) as price', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['type' => $credit_type])
                ->andWhere(['member_type' => MemberTypeEnum::MEMBER])
                ->andWhere(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();

            foreach ($data as &$datum) {
                $datum['price'] = abs($datum['price']);
            }

            return $data;
        }, $fields, $time, $format);
    }
}
