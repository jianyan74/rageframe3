<?php

namespace common\models\extend\printer;

use yii\base\Model;

/**
 * 芯烨云
 *
 * Class XpYun
 * @package common\models\hardware
 * @author jianyan74 <751393839@qq.com>
 */
class XpYun extends Model
{
    public $terminal_number;
    public $app_id;
    public $app_secret_key;
    public $print_num = 1;

    public function rules()
    {
        return [
            [['terminal_number', 'app_id', 'app_secret_key', 'print_num'], 'required'],
            [['terminal_number', 'app_id'], 'string'],
            [['print_num'], 'integer', 'min' => 1, 'max' => 9],
        ];
    }

    public function attributeLabels()
    {
        return [
            'terminal_number' => '终端号',
            'app_id' => '应用ID',
            'app_secret_key' => '应用密钥',
            'print_num' => '打印联数'
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeHints()
    {
        return [
            'terminal_number' => '开放平台：https://platform.xpyun.net',
            'app_id' => '芯烨云平台注册用户名（开发者 ID）',
            'app_secret_key' => '开发者秘钥',
            'print_num' => '同一个记录，打印的数量，区间范围为 1-9'
        ];
    }
}
