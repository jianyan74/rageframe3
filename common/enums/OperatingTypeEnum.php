<?php

namespace common\enums;

use yii\helpers\Html;

/**
 * 运营类型
 *
 * Class OperatingTypeEnum
 * @package common\enums
 */
class OperatingTypeEnum extends BaseEnum
{
    const SELF_SUPPORT = 1;
    const ENTER = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::SELF_SUPPORT => '自营',
            self::ENTER => '加盟',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::SELF_SUPPORT => Html::tag('span', self::getValue(self::SELF_SUPPORT), array_merge(
                [
                    'class' => "label label-outline-purple",
                ]
            )),
            self::ENTER => Html::tag('span', self::getValue(self::ENTER), array_merge(
                [
                    'class' => "label label-outline-info",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}
