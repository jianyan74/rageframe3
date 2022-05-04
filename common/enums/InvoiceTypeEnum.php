<?php

namespace common\enums;

use yii\helpers\Html;

/**
 * Class InvoiceTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceTypeEnum extends BaseEnum
{
    const COMPANY = 1;
    const PERSONAGE = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::COMPANY => '公司',
            self::PERSONAGE => '个人',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::COMPANY => Html::tag('span', self::getValue(self::COMPANY), array_merge(
                [
                    'class' => "label label-outline-primary",
                ]
            )),
            self::PERSONAGE => Html::tag('span', self::getValue(self::PERSONAGE), array_merge(
                [
                    'class' => "label label-outline-success",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}
