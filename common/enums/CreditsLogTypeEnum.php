<?php

namespace common\enums;

/**
 * Class CreditsLogType
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogTypeEnum extends BaseEnum
{
    // 金额类型
    const USER_MONEY = 'user_money';
    const CONSUME_MONEY = 'consume_money';

    // 积分类型
    const USER_INTEGRAL = 'user_integral';

    // 成长值类型
    const USER_GROWTH = 'user_growth';

    // 节约金额
    const ECONOMIZE = 'economize_money';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::USER_MONEY => '余额日志',
            self::USER_INTEGRAL => '积分日志',
            self::USER_GROWTH => '成长值日志',
            self::CONSUME_MONEY => '消费日志',
        ];
    }
}
