<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\enums\MemberAuthOauthClientEnum;

/**
 * 第三方授权
 *
 * Class ThirdPartyController
 * @package backend\controllers
 */
class ThirdPartyController extends BaseController
{
    public $member_id;

    public function init()
    {
        parent::init();

        $this->member_id = Yii::$app->request->get('member_id');
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $thirdParty = [
            MemberAuthOauthClientEnum::WECHAT => [
                'name' => MemberAuthOauthClientEnum::WECHAT,
                'client' => '无',
                'title' => MemberAuthOauthClientEnum::getValue(MemberAuthOauthClientEnum::WECHAT),
                'status' => StatusEnum::DISABLED
            ]
        ];

        $auth = Yii::$app->services->memberAuth->findByMemberId($this->member_id);
        $auth = ArrayHelper::arrayKey($auth, 'oauth_client');
        foreach ($thirdParty as &$item) {
            if (isset($auth[$item['name']])) {
                $item['status'] = StatusEnum::ENABLED;
                $item['client'] = $auth[$item['name']]['oauth_client_user_id'];
            }
        }

        return $this->render($this->action->id, [
            'thirdParty' => $thirdParty,
            'memberId' => $this->member_id,
        ]);
    }

    /**
     * 绑定
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBindingWechat($member_id)
    {
        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        $data = Yii::$app->wechatService->qrcode->syncCreateByData([
            'name' => '账号绑定',
            'model_type' => 1,
            'expire_seconds' => 5 * 60,
            'extend' => [
                'member_id' => $member_id
            ],
        ]);

        $data->save();

        return $qr->setText($data['url'])
            ->setSize(200)
            ->setMargin(7)
            ->writeString();
    }

    /**
     * 解绑
     *
     * @param $uuid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUnBind($type, $member_id)
    {
        Yii::$app->services->memberAuth->unBind($type, $member_id);

        return $this->message("解绑成功", $this->redirect(['index', 'member_id' => $member_id]));
    }
}
