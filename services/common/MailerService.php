<?php

namespace services\common;

use Yii;
use yii\base\InvalidConfigException;
use common\queues\MailerJob;

/**
 * Class MailerService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class MailerService
{
    /**
     * 消息队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * 发送邮件
     *
     * ```php
     *       Yii::$app->services->mailer->send($user, $email, $subject, $template)
     * ```
     * @param object $user 用户信息
     * @param string $email 邮箱
     * @param string $subject 标题
     * @param string $template 对应邮件模板
     * @throws \yii\base\InvalidConfigException
     */
    public function send($user, $email, $subject, $template, $data = [])
    {
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new MailerJob([
                'user' => $user,
                'email' => $email,
                'subject' => $subject,
                'template' => $template,
                'data' => $data,
            ]));

            return $messageId;
        }

        return $this->realSend($user, $email, $subject, $template, $data);
    }

    /**
     * 发送
     *
     * @param $user
     * @param $email
     * @param $subject
     * @param $template
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function realSend($user, $email, $subject, $template, $data = [])
    {
        try {
            $this->setConfig();
            $result = Yii::$app->mailer
                ->compose($template, [
                    'user' => $user,
                    'data' => $data,
                ])
                ->setFrom([$this->config['smtp_username'] => $this->config['smtp_name']])
                ->setTo($email)
                ->setSubject($subject)
                ->send();

            Yii::info($result);

            return $result;
        } catch (InvalidConfigException $e) {
            Yii::error($e->getMessage());
        }

        return false;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setConfig()
    {
        $this->config = Yii::$app->services->config->backendConfigAll();

        Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $this->config['smtp_host'],
                'username' => $this->config['smtp_username'],
                'password' => $this->config['smtp_password'],
                'port' => $this->config['smtp_port'],
                'encryption' => empty($this->config['smtp_encryption']) ? 'tls' : 'ssl',
            ],
        ]);
    }
}
