<?php

namespace common\enums;

/**
 * 转账类型
 *
 * Class TransferTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TransferTypeEnum extends BaseEnum
{
    const OFFLINE = 1;
    const BALANCE = 2;
    const WECHAT_BANK_CARD = 10;
    const WECHAT_BALANCE = 11;
    const ALI_BANK_CARD = 20;
    const ALI_BALANCE = 21;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::OFFLINE => '线下转账',
            self::BALANCE => '在线转账到余额',
            self::WECHAT_BANK_CARD => '微信转账银行卡',
            self::WECHAT_BALANCE => '微信转账到零钱',
            self::ALI_BANK_CARD => '支付宝转账银行卡',
            self::ALI_BALANCE => '支付宝转账到零钱',
        ];
    }

    /**
     * 即时到账
     *
     * @return array
     */
    public static function instant(): array
    {
        return [
            self::BALANCE, // 余额
            self::WECHAT_BALANCE, // 微信转账到零钱
            self::ALI_BALANCE // 支付宝转账到零钱
        ];
    }

    /**
     * @param $key
     * @return array|string[]
     */
    public static function getType($key)
    {
        switch ($key) {
            case AccountTypeEnum::WECHAT :
                return [
                    self::OFFLINE => '线下转账',
                    self::WECHAT_BALANCE => '微信转账到零钱',
                ];
                break;
            case AccountTypeEnum::ALI :
                return [
                    self::OFFLINE => '线下转账',
                    self::ALI_BALANCE => '支付宝转账到零钱',
                ];
                break;
            case AccountTypeEnum::WECHAT_MINI :
                return [
                    self::OFFLINE => '线下转账',
                    self::WECHAT_BALANCE => '微信转账到零钱',
                ];
                break;
            case AccountTypeEnum::UNION :
                return [
                    self::OFFLINE => '线下转账',
                    self::WECHAT_BANK_CARD => '微信转账银行卡',
                    // self::ALI_BANK_CARD => '支付宝转账银行卡',
                ];
                break;
            case AccountTypeEnum::BALANCE :
                return [
                    self::BALANCE => '在线转账到余额',
                ];
                break;
            default :
                return [];
                break;
        }
    }
}
