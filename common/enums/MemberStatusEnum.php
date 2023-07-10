<?php

namespace common\enums;

use common\helpers\Html;

/**
 * Class MemberStatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberStatusEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '正常',
            self::DISABLED => '黑名单',
            self::DELETE => '已注销',
        ];
    }

    /**
     * @param $status
     * @return mixed|string
     */
    public static function html($status)
    {
        $listBut = [
            self::ENABLED => Html::tag('span', '正常', [
                'class' => "label label-outline-success",
            ]),
            self::DISABLED => Html::tag('span', '黑名单', [
                'class' => "label label-outline-default label-sm",
            ]),
            self::DELETE => Html::tag('span', '已注销', [
                'class' => "label label-outline-default label-sm",
            ]),
        ];

        return $listBut[$status] ?? '';
    }
}
