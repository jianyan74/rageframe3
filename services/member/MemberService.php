<?php

namespace services\member;

use Yii;
use common\models\base\User;
use common\components\Service;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\enums\MemberTypeEnum;
use common\helpers\TreeHelper;
use common\helpers\EchantsHelper;
use common\enums\MemberLevelBuyTypeEnum;
use common\enums\AccessTokenGroupEnum;

/**
 * Class MemberService
 * @package services\member
 */
class MemberService extends Service
{
    /**
     * 用户
     *
     * @var Member
     */
    protected $member;

    /**
     * @param $id
     * @return array|Member|\yii\db\ActiveRecord|null
     */
    public function get($id)
    {
        if (!$this->member || $this->member['id'] != $id) {
            $this->member = $this->findById($id);
        }

        return $this->member;
    }

    /**
     * @param Member $member
     * @return $this
     */
    public function set(Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * 获取当前登录的用户ID
     *
     * @return int|string
     */
    public function getAutoId()
    {
        if (in_array(Yii::$app->id, AppEnum::api())) {
            $member_id = Yii::$app->user->identity->member_id ?? 0;
            if (empty($member_id)) {
                return Yii::$app->user->id ?? 0;
            }

            return $member_id;
        }

        return Yii::$app->user->id ?? 0;
    }

    /**
     * 购买等级
     *
     * @param Member $member
     * @param $validity
     * @param $level
     * @param int $buy_type
     */
    public function buyLevel(Member $member, $validity, $level, $buy_type = MemberLevelBuyTypeEnum::BUY)
    {
        $time = time();
        if ($member->level_expiration_time > time()) {
            $time = $member->level_expiration_time;
        }

        // 修改会员到期时间
        if ($member->current_level < $level) {
            Member::updateAll([
                'level_expiration_time' => $time + $validity,
                'current_level' => $level,
                'level_buy_type' => $buy_type
            ], ['id' => $member->id]);
        } else {
            Member::updateAll([
                'level_expiration_time' => $time + $validity,
                'level_buy_type' => $buy_type
            ], ['id' => $member->id]);
        }
    }

    /**
     * 获取所有下级id
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChildById($id, $level = 3)
    {
        $member = $this->get($id);

        return Member::find()
            ->select(['id', 'level'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['<=', 'level', $member->level + $level])
            ->andWhere(['like', 'tree', $member->tree . TreeHelper::prefixTreeKey($member->id) . '%', false])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();
    }

    /**
     * @return int|string
     */
    public function getCountByType($type = MemberTypeEnum::MEMBER, $merchant_id = '')
    {
        return Member::find()
            ->select('id')
            ->andWhere(['>', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->count();
    }

    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type, $memberType = MemberTypeEnum::MEMBER)
    {
        $fields = [
            'count' => '注册会员人数',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) use ($memberType) {
            return Member::find()
                ->select(['count(id) as count', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
                ->andWhere(['type' => $memberType])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * 会员来源
     *
     * @param $type
     * @return array
     */
    public function getSourceStat($memberType = MemberTypeEnum::MEMBER)
    {
        $fields = AccessTokenGroupEnum::getMap();

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime('all');
        // 获取数据
        return EchantsHelper::pie(function ($start_time, $end_time) use ($fields, $memberType) {
            $data = Member::find()
                ->select(['count(id) as value', 'source'])
                ->where(['type' => $memberType, 'status' => StatusEnum::ENABLED])
                ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
                ->groupBy(['source'])
                ->asArray()
                ->all();

            foreach ($data as &$datum) {
                $name = AccessTokenGroupEnum::getValue($datum['source']);
                $datum['name'] = !empty($name) ? $name : '未知';
            }

            return [$data, $fields];
        }, $time);
    }

    /**
     * 会员等级
     *
     * @return array
     */
    public function getLevelStat($memberType = MemberTypeEnum::MEMBER)
    {
        $fields = Yii::$app->services->memberLevel->getMap();

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime('all');
        // 获取数据
        return EchantsHelper::pie(function ($start_time, $end_time) use ($fields, $memberType) {
            $data = Member::find()
                ->select(['count(id) as value', 'current_level'])
                ->where(['type' => $memberType, 'status' => StatusEnum::ENABLED])
                ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
                ->groupBy(['current_level'])
                ->asArray()
                ->all();

            foreach ($data as &$datum) {
                $name = $fields[$datum['current_level']] ?? '';
                $datum['name'] = !empty($name) ? $name : '未知';
            }

            return [$data, $fields];
        }, $time);
    }

    /**
     * 记录行为
     *
     * @param User $member
     * @param bool $saveAction
     * @throws \yii\base\InvalidConfigException
     */
    public function lastLogin($member)
    {
        // 记录访问次数
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->services->base->getUserIp();
        $member->save();
    }

    /**
     * @param $level
     * @param $type
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function hasLevel($level, $type, $merchant_id)
    {
        return Member::find()
            ->where(['current_level' => $level])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $merchant_id])
            ->andWhere(['type' => $type])
            ->one();
    }

    /**
     * 写入条件查询
     *
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null|Member
     */
    public function findByCondition(array $condition)
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->one();
    }

    /**
     * 写入条件查询
     *
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllByCondition(array $condition)
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Member
     */
    public function findById($id, $select = ['*'])
    {
        return Member::find()
            ->select($select)
            ->where(['id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->one();
    }

    /**
     * @param $ids
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByIds($ids = [], $type = MemberTypeEnum::MEMBER, $select = ['*'])
    {
        return Member::find()
            ->select($select)
            ->filterWhere(['in', 'id', $ids])
            ->andWhere(['type' => $type])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->all();
    }

    /**
     * @param $promoterCode
     * @return array|\yii\db\ActiveRecord|null|Member
     */
    public function findByPromoterCode($promoterCode)
    {
        return Member::find()
            ->where(['promoter_code' => $promoterCode])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->one();
    }
}
