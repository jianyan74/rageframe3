<?php

namespace addons\Member\common\models;

use yii\base\Model;

/**
 * Class SettingForm
 * @package addons\Member\common\models
 */
class SettingForm extends Model
{
    public $cancel_audit_status = 0;
    public $cancel_protocol_title = '会员注销协议';
    public $cancel_protocol = '暂无';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cancel_audit_status'], 'string', 'max' => 255],
            [['cancel_protocol_title'], 'string', 'max' => 100],
            [['cancel_protocol'], 'string'],
            [['cancel_protocol_title', 'cancel_protocol'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'cancel_audit_status' => '注销审核',
            'cancel_protocol_title' => '注销协议标题',
            'cancel_protocol' => '注销协议内容',
        ];
    }
}
