<?php

namespace common\enums;

use common\helpers\Html;

/**
 * Class WhetherEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WhetherEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '是',
            self::DISABLED => '否',
        ];
    }

    /**
     * @return array
     */
    public static function getOpenMap(): array
    {
        return [
            self::ENABLED => '开启',
            self::DISABLED => '关闭',
        ];
    }

    /**
     * @return array
     */
    public static function getShowMap(): array
    {
        return [
            self::ENABLED => '显示',
            self::DISABLED => '隐藏',
        ];
    }

    /**
     * 是否标签
     *
     * @param int $status
     * @return mixed
     */
    public static function html(int $status)
    {
        $listBut = [
            self::ENABLED => Html::tag('span', '是', [
                'class' => "label label-outline-success label-sm",
            ]),
            self::DISABLED => Html::tag('span', '否', [
                'class' => "label label-outline-danger label-sm",
            ]),
        ];

        return $listBut[$status] ?? '';
    }
}
