<?php

namespace common\enums;

/**
 * 会员升级类型
 *
 * Class MemberLevelUpgradeTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberLevelUpgradeTypeEnum extends BaseEnum
{
    const CONSUMPTION_INTEGRAL = 1;
    const CONSUMPTION_MONEY = 2;
    const CONSUMPTION_GROWTH = 3;

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::CONSUMPTION_INTEGRAL => '累计积分',
            self::CONSUMPTION_MONEY => '累计消费金额',
            self::CONSUMPTION_GROWTH => '累计成长值',
        ];
    }

    /**
     * 等级对应字段
     *
     * @param $key
     * @return string
     */
    public static function getLevelFieldByKey($key): string
    {
        $map = [
            self::CONSUMPTION_INTEGRAL => 'integral',
            self::CONSUMPTION_MONEY => 'money',
            self::CONSUMPTION_GROWTH => 'growth',
        ];

        return $map[$key] ?? '';
    }

    /**
     * 账号对应字段
     *
     * @param $key
     * @return string
     */
    public static function getAccountFieldByKey($key): string
    {
        $map = [
            self::CONSUMPTION_INTEGRAL => 'accumulate_integral',
            self::CONSUMPTION_MONEY => 'consume_money',
            self::CONSUMPTION_GROWTH => 'accumulate_growth',
        ];

        return $map[$key] ?? '';
    }
}
