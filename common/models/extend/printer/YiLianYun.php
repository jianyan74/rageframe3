<?php

namespace common\models\extend\printer;

/**
 * Class YiLianYun
 * @package common\models\hardware
 * @author jianyan74 <751393839@qq.com>
 */
class YiLianYun extends \yii\base\Model
{
    public $terminal_number;
    public $secret_key;
    public $app_id;
    public $app_secret_key;
    public $print_num = 1;

    public function rules()
    {
        return [
            [['terminal_number', 'secret_key', 'app_id', 'app_secret_key', 'print_num'], 'required'],
            [['terminal_number', 'secret_key', 'app_id'], 'integer'],
            [['print_num'], 'integer', 'min' => 1, 'max' => 9],
        ];
    }

    public function attributeLabels()
    {
        return [
            'terminal_number' => '终端号',
            'secret_key' => ' 密钥',
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
            'app_id' => ' 易联云开放平台创建应用获取',
            'app_secret_key' => '易联云开放平台创建应用获取',
            'print_num' => '同一个记录，打印的数量，区间范围为 1-9'
        ];
    }
}
