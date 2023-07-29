<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;
use common\helpers\Html;

/**
 * Class QualificationTypeEnum
 * @package addons\WechatMini\common\enums\video
 * @author jianyan74 <751393839@qq.com>
 */
class QualificationTypeEnum extends BaseEnum
{
    const NOT = 0;
    const REQUIRED = 1;
    const OPTIONAL = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::NOT => '不需要',
            self::REQUIRED => '必填',
            self::OPTIONAL => '选填',
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
            self::NOT => Html::tag('span', self::getValue($status), [
                'class' => "label label-outline-success label-sm",
            ]),
            self::REQUIRED => Html::tag('span', self::getValue($status), [
                'class' => "label label-outline-danger label-sm",
            ]),
            self::OPTIONAL => Html::tag('span', self::getValue($status), [
                'class' => "label label-outline-primary label-sm",
            ]),
        ];

        return $listBut[$status] ?? '';
    }
}
