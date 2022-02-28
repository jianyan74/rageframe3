<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;
use common\models\member\Auth;

/**
 * app 推送
 *
 * Class AppPushJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class AppPushJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var array
     */
    public $title;

    /**
     * @var array
     */
    public $content;

    /**
     * @var array
     */
    public $type;

    /**
     * @var Auth
     */
    public $auth;

    /**
     * @var array
     */
    public $transmissionContent = [];

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->services->extendAppPush->realPlus($this->type, $this->title, $this->content, $this->auth, $this->transmissionContent);
    }
}