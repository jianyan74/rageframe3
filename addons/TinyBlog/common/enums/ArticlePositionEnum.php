<?php

namespace addons\TinyBlog\common\enums;

use common\enums\BaseEnum;

/**
 * Class ArticlePositionEnum
 * @package addons\TinyBlog\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ArticlePositionEnum extends BaseEnum
{
    const HOT = 1;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::HOT => '热门',
        ];
    }

    /**
     * 获取推荐位
     *
     * @param $position
     * @return string
     */
    public static function position($position)
    {
        return "position & {$position} = {$position}";
    }

    /**
     * 将两个参数进行按位与运算
     * 不为0则表示$contain属于$pos
     *
     * @param $pos
     * @param int $contain
     * @return bool
     */
    public static function checkPosition($pos, $contain = 0)
    {
        $res = $pos & $contain;
        return $res !== 0 ? true : false;
    }
}
