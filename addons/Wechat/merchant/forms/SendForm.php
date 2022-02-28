<?php

namespace addons\Wechat\merchant\forms;

use Yii;
use common\helpers\ArrayHelper;
use addons\Wechat\common\models\MassRecord;
use common\enums\StatusEnum;
use addons\Wechat\common\enums\RuleModuleEnum;

/**
 * Class SendForm
 * @package merchant\modules\wechat\models
 * @author jianyan74 <751393839@qq.com>
 */
class SendForm extends MassRecord
{
    public $text;
    public $image;
    public $news;
    public $video;
    public $voice;

    /**
     * 群发消息
     *
     * @var array
     */
    protected $sendMethod = [
        'text' => 'sendText',
        'news' => 'sendNews',
        'voice' => 'sendVoice',
        'image' => 'sendImage',
        'video' => 'sendVideo',
        'card' => 'sendCard',
    ];

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['send_type', 'integer'];
        $rules[] = [['text', 'image', 'news', 'video', 'voice'], 'string'];
        $rules[] = [['tag_id'], 'verifyRequired'];

        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'send_type' => '发送类型'
        ]);
    }

    public function verifyRequired($attribute)
    {
        if ($this->module == RuleModuleEnum::TEXT && !$this->text) {
            $this->addError($attribute, '请填写内容');
        }

        if ($this->module == RuleModuleEnum::IMAGE && !$this->image) {
            $this->addError($attribute, '请选择图片');
        }

        if ($this->module == RuleModuleEnum::VIDEO && !$this->video) {
            $this->addError($attribute, '请选择视频');
        }

        if ($this->module == RuleModuleEnum::VOICE && !$this->voice) {
            $this->addError($attribute, '请选择语音');
        }

        if ($this->module == RuleModuleEnum::NEWS && !$this->news) {
            $this->addError($attribute, '请选择图文');
        }
    }

    public function afterFind()
    {
        if ($this->module == RuleModuleEnum::TEXT) {
            $this->text = $this->data;
        }

        if ($this->module == RuleModuleEnum::IMAGE) {
            $this->image = $this->data;
        }

        if ($this->module == RuleModuleEnum::VIDEO) {
            $this->video = $this->data;
        }

        if ($this->module == RuleModuleEnum::VOICE) {
            $this->voice = $this->data;
        }

        if ($this->module == RuleModuleEnum::NEWS) {
            $this->news = $this->data;
        }

        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function beforeSave($insert)
    {
        if ($this->module == RuleModuleEnum::TEXT) {
            $this->data = $this->text;
        }

        if ($this->module == RuleModuleEnum::IMAGE) {
            $this->data = $this->image;
        }

        if ($this->module == RuleModuleEnum::VIDEO) {
            $this->data = $this->video;
        }

        if ($this->module == RuleModuleEnum::VOICE) {
            $this->data = $this->voice;
        }

        if ($this->module == RuleModuleEnum::NEWS) {
            $this->data = $this->news;
        }

        $this->tag_name = '全部粉丝';
        if ($this->tag_id > 0) {
            $tag = Yii::$app->wechatService->fansTags->findById($this->tag_id);
            $this->tag_name = $tag['name'];
            $this->fans_num = $tag['count'];
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 群发消息
        if ($this->send_type == StatusEnum::ENABLED && $this->send_status != StatusEnum::ENABLED) {
            Yii::$app->wechatService->message->send($this);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}