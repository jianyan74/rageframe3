<?php

namespace common\enums;

/**
 * 提现状态枚举
 *
 * Class WithdrawTransferStatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WithdrawTransferStatusEnum extends BaseEnum
{
    const APPLY = 0;
    const APPLY_AGREE = 1;
    const TRANSFER_IN_PROGRESS = 2;
    const TRANSFER_SUCCESS = 3;
    const APPLY_REFUSE = -1;
    const TRANSFER_ERROR = -2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::APPLY => '审核中',
            self::APPLY_AGREE => '待转账', // 审核通过
            self::TRANSFER_IN_PROGRESS => '转账中',
            self::TRANSFER_SUCCESS => '转账成功',
            self::APPLY_REFUSE => '审核拒绝',
            self::TRANSFER_ERROR => '转账失败',
        ];
    }
}