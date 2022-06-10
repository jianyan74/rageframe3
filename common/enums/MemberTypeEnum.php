<?php

namespace common\enums;

/**
 * Class MemberTypeEnum
 * @package common\enums
 */
class MemberTypeEnum extends BaseEnum
{
    const MEMBER = 1;
    const MANAGER = 2;
    const MERCHANT = 3;
    const SHOP = 4;
    const STORE = 5;
    const ROBOT = 100;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::MEMBER => '会员',
            self::MANAGER => '后台管理员',
            self::MERCHANT => '商家管理员',
            self::SHOP => '店铺管理员',
            self::STORE => '门店管理员',
            self::ROBOT => '机器人',
        ];
    }
}
