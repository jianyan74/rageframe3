<?php

namespace addons\Wechat\merchant\forms;

use yii\base\Model;

/**
 * Class ReplyDefaultForm
 * @package addons\Wechat\merchant\forms
 */
class ReplyDefaultForm extends Model
{
    public $follow_content;
    public $default_content;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['follow_content', 'default_content'], 'string', 'max' => 200],
            [['follow_content', 'default_content'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'follow_content' => '关注回复关键字',
            'default_content' => '默认回复关键字',
        ];
    }
}