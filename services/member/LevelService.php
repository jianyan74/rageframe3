<?php

namespace services\member;

use Yii;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\enums\StatusEnum;
use common\models\member\Account;
use common\models\member\Level;
use common\models\member\Member;
use common\enums\MemberLevelUpgradeTypeEnum;
use common\enums\MemberLevelAutoUpgradeTypeEnum;

/**
 * Class LevelService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class LevelService extends Service
{
    /**
     * @var int $timeout 过期时间
     */
    private $timeout = 20;

    /**
     * @param Member $member
     * @return bool|Level|mixed|\yii\db\ActiveRecord
     */
    public function getLevelByMember(Member $member)
    {
        /** @var Account $account */
        $account = $member->account;

        return $this->getLevel(
            $member->current_level,
            $member->level_expiration_time,
            $account->consume_money,
            $account->accumulate_integral,
            $account->accumulate_growth
        );
    }

    /**
     * 获取用户可升等级信息
     *
     * @param int $current_level 当前级别
     * @param int $level_expiration_time 过期时间
     * @param float $money 累计消费金额
     * @param int $integral 累计积分
     * @param int $growth 累计成长值
     * @return Level|false|mixed|\yii\db\ActiveRecord
     */
    public function getLevel(int $current_level, int $level_expiration_time, float $money, int $integral, int $growth)
    {
        if (!($levels = $this->getLevelForCache())) {
            return false;
        }

        // 未开启自动升级
        $config = Yii::$app->services->memberLevelConfig->one(0);
        if ($config->auto_upgrade_type == MemberLevelAutoUpgradeTypeEnum::CLOSE) {
            return false;
        }

        // 开启会员才可升级且会员已过期
        if (
            $config->auto_upgrade_type == MemberLevelAutoUpgradeTypeEnum::VIP_AUTO &&
            $level_expiration_time < time()
        ) {
            return false;
        }

        foreach ($levels as $level) {
            if (!$this->getMiddle($level, $config->upgrade_type, $money, $integral, $growth)) {
                continue;
            }

            if ($current_level < $level->level) {
                return $level;
            }
        }

        return false;
    }

    /**
     * 根据商户id获取等级列表
     * @param int $merchant_id
     * @return array|Level[]|mixed|\yii\db\ActiveRecord[]
     */
    public function getLevelForCache()
    {
        $key = 'levelList';
        if (!($list = Yii::$app->cache->get($key))) {
            $list = $this->findAll();

            Yii::$app->cache->set($key, $list, $this->timeout);
        }

        return $list;
    }

    /**
     * @return array
     */
    public function getMap()
    {
        $list = ArrayHelper::arraySort($this->findAll(0), 'level');

        return ArrayHelper::map($list, 'level', 'name');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Level::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => 0])
            ->orderBy(['level' => SORT_DESC, 'id' => SORT_DESC])
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllByEdit()
    {
        return Level::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy(['level' => SORT_ASC, 'id' => SORT_DESC])
            ->all();
    }

    /**
     * @param $level
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByLevel($level)
    {
        return Level::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['level' => $level])
            ->one();
    }

    /**
     * @param Level $level
     * @param float $money 累计消费
     * @param int $integral 累计积分
     * @return array|bool|mixed
     */
    private function getMiddle(Level $level, $auto_upgrade_type, $money, $integral, $growth)
    {
        if (!$level) {
            return false;
        }

        switch ($auto_upgrade_type) {
            case MemberLevelUpgradeTypeEnum::CONSUMPTION_INTEGRAL:
                if (abs($integral) >= $level->integral) {
                    return true;
                }
                break;
            case MemberLevelUpgradeTypeEnum::CONSUMPTION_MONEY:
                if (abs($money) >= $level->money) {
                    return true;
                }
                break;
            case MemberLevelUpgradeTypeEnum::CONSUMPTION_GROWTH:
                if (abs($growth) >= $level->growth) {
                    return true;
                }
                break;
        }

        return false;
    }
}
