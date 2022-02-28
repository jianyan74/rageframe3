<?php

namespace addons\Wechat\merchant\forms;

use yii\base\Model;

/**
 * Class HistoryForm
 * @package merchant\modules\wechat\forms
 * @author jianyan74 <751393839@qq.com>
 */
class HistoryForm extends Model
{
    public $history_status = 1;
    public $history_utilization_status = 1;
    public $history_message_date = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['history_status', 'history_utilization_status', 'history_message_date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'history_status' => '开启历史消息记录',
            'history_message_date' => '历史消息记录天数',
            'history_utilization_status' => '开启利用率统计',
        ];
    }
}
