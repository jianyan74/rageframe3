<?php

namespace common\models\extend\printer;

/**
 * Class FeiE
 * @package common\models\hardware
 * @author jianyan74 <751393839@qq.com>
 */
class FeiE extends \yii\base\Model
{
    public $ukey;
    public $user;
    public $app_id;
    public $sn;
    public $print_num = 1;

    public function rules()
    {
        return [
            [['ukey', 'user', 'sn', 'print_num'], 'required'],
            [['sn'], 'integer'],
            [['print_num'], 'integer', 'min' => 1, 'max' => 9],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user' => 'USER',
            'ukey' => ' UKEY',
            'sn' => 'sn',
            'print_num' => '打印联数'
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeHints()
    {
        return [
            'user' => ' 飞鹅后台：个人中心->用户信息获取。http://feieyun.com/open/index.html',
            'ukey' => '飞鹅后台：个人中心->用户信息获取。http://feieyun.com/open/index.html',
            'sn' => '打印机编号',
            'print_num' => '同一个记录，打印的数量，区间范围为 1-9'
        ];
    }
}
