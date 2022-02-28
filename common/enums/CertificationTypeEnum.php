<?php

namespace common\enums;

use yii\helpers\Html;

/**
 * Class CertificationTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CertificationTypeEnum extends BaseEnum
{
    const UNVERIFIED = 0;
    const COMPANY = 1;
    const INDIVIDUAL = 2;
    const GOVERNMENT = 3;
    const OTHER_ORGANIZATION = 4;
    const PERSONAGE = 5;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::UNVERIFIED => '未认证',
            self::COMPANY => '企业认证',
            // self::INDIVIDUAL => '个体户认证',
            // self::GOVERNMENT => '政府和事业单位',
            // self::OTHER_ORGANIZATION => '其他组织',
            self::PERSONAGE => '个人认证',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::UNVERIFIED => Html::tag('span', self::getValue(self::UNVERIFIED), array_merge(
                [
                    'class' => "label label-outline-default",
                ]
            )),
            self::COMPANY => Html::tag('span', self::getValue(self::COMPANY), array_merge(
                [
                    'class' => "label label-outline-primary",
                ]
            )),
            self::GOVERNMENT => Html::tag('span', self::getValue(self::GOVERNMENT), array_merge(
                [
                    'class' => "label label-outline-primary",
                ]
            )),
            self::INDIVIDUAL => Html::tag('span', self::getValue(self::INDIVIDUAL), array_merge(
                [
                    'class' => "label label-outline-primary",
                ]
            )),
            self::OTHER_ORGANIZATION => Html::tag('span', self::getValue(self::OTHER_ORGANIZATION), array_merge(
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
