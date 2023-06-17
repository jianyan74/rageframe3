<?php

namespace common\enums;

use yii\helpers\Html;

/**
 * 提现账号类别
 *
 * Class AccountTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AccountTypeEnum extends BaseEnum
{
    const BALANCE = 1;
    const UNION = 10;
    const WECHAT = 20;
    const WECHAT_MINI = 21;
    const ALI = 30;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::UNION => '银联卡',
            self::WECHAT => '微信',
            self::WECHAT_MINI => '微信小程序',
            self::ALI => '支付宝',
            self::BALANCE => '余额',
        ];
    }

    /**
     * 经常使用
     *
     * @return string[]
     */
    public static function frequentlyUse()
    {
        return [
            self::UNION => '银联卡',
            self::WECHAT => '微信',
            self::ALI => '支付宝',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::UNION => Html::tag('span', self::getValue(self::UNION), array_merge(
                [
                    'class' => "blue",
                ]
            )),
            self::BALANCE => Html::tag('span', self::getValue(self::BALANCE), array_merge(
                [
                    'class' => "gray",
                ]
            )),
            self::ALI => Html::tag('span', self::getValue(self::ALI), array_merge(
                [
                    'class' => "cyan",
                ]
            )),
            self::WECHAT => Html::tag('span', self::getValue(self::WECHAT), array_merge(
                [
                    'class' => "green",
                ]
            )),
            self::WECHAT_MINI => Html::tag('span', self::getValue(self::WECHAT_MINI), array_merge(
                [
                    'class' => "green",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}
