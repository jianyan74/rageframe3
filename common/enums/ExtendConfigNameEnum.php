<?php

namespace common\enums;

use yii\base\Model;
use common\models\extend\printer\FeiE;
use common\models\extend\printer\YiLianYun;
use common\models\extend\printer\XpYun;

/**
 * 扩展配置标识
 *
 * Class ExtendConfigNameEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ExtendConfigNameEnum extends BaseEnum
{
    /** @var string 小票打印机 */
    const YI_LIAN_YUN = 'yiLianYun';
    const FEI_E = 'feiE';
    const XP_YUN = 'xpYun';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::YI_LIAN_YUN => '易联云',
            self::FEI_E => '飞鹅云',
            self::XP_YUN => '芯烨云',
        ];
    }

    /**
     * 模型
     *
     * @param $key
     * @return Model|string
     */
    public static function getModelValue($key)
    {
        $class = static::getModelMap()[$key] ?? '';
        if (!empty($class)) {
            $class = new $class();
        }

        return $class;
    }

    /**
     * 模型对应
     *
     * @return array|string[]
     */
    public static function getModelMap(): array
    {
        return [
            self::YI_LIAN_YUN => YiLianYun::class,
            self::FEI_E => FeiE::class,
            self::XP_YUN => XpYun::class,
        ];
    }

    /**
     * 组别
     *
     * @param $key
     * @return string|string[]
     */
    public static function getGroupValue($key)
    {
        return static::getGroup()[$key] ?? '';
    }

    /**
     * 组别对应
     *
     * @param $type
     * @return \string[][]
     */
    public static function getGroup()
    {
        return [
            ExtendConfigTypeEnum::RECEIPT_PRINTER => [
                self::YI_LIAN_YUN => '易联云',
                self::FEI_E => '飞鹅云',
                self::XP_YUN => '芯烨云',
            ],
        ];
    }
}
