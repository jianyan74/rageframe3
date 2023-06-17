<?php

namespace common\forms;

use yii\base\Model;

/**
 * Class LogisticsForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LogisticsForm extends Model
{
    /**
     * 接口返回状态码
     *
     * @var int
     */
    public $code;
    /**
     * 接口返回说明
     *
     * @var int
     */
    public $message;
    /**
     * 物流公司简称
     *
     * @var string
     */
    public $company;
    /**
     * 物流单号
     *
     * @var string
     */
    public $no;
    /**
     * 重组数据
     *
     * @var array
     */
    public $list = [];
    /**
     * 原始数据
     *
     * @var array
     */
    public $original = [];
}
