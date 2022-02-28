<?php

namespace common\enums;

use common\helpers\Html;

/**
 * 状态枚举
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StatusEnum extends BaseEnum
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
            self::ENABLED => '启用',
            self::DISABLED => '禁用',
            // self::DELETE => '已删除',
        ];
    }

    /**
     * @param $status
     * @return mixed|string
     */
    public static function html($status)
    {
        $listBut = [
            self::ENABLED => Html::tag('span', '已启用', [
                'class' => "label label-outline-primary",
            ]),
            self::DISABLED => Html::tag('span', '已禁用', [
                'class' => "label label-outline-default label-sm",
            ]),
        ];

        return $listBut[$status] ?? '';
    }
}
