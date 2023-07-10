<?php

namespace common\enums;

use common\models\member\Member;

/**
 * Class SubscriptionActionEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionActionEnum extends BaseEnum
{
    /** @var string 提醒 */
    const ABNORMAL_LOGIN = 'abnormal_login';
    const ERROR = 'error';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ABNORMAL_LOGIN => '连续异常登录',
            self::ERROR => '请求 500 错误',
        ];
    }

    /**
     * 默认值
     *
     * @param $action
     * @return array|string[]
     */
    public static function default($action)
    {
        $data = [
            self::ERROR => [
                'title' => '有一条 500 错误日志',
                'content' => '具体看全局日志',
                // 辅助说明表
                'tables' => [
                    // [
                    //     'title' => '用户',
                    //     'prefix' => 'member',
                    //     'tableName' => Member::tableName(),
                    //     'filterField' => []
                    // ]
                ],
            ],
            self::ABNORMAL_LOGIN => [
                'title' => '有一条异常错误日志',
                'content' => '{username} 连续 {attempts} 次错误输入账号密码',
                'tables' => [

                ]
            ],
        ];

        return $data[$action] ?? [];
    }
}
