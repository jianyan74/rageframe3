<?php

namespace addons\Wechat\services;

use Yii;
use Exception;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\enums\AccessTokenGroupEnum;
use common\enums\MemberTypeEnum;
use addons\Wechat\common\models\Qrcode;
use addons\Wechat\common\models\QrcodeStat;
use addons\Wechat\common\enums\QrcodeStatTypeEnum;
use addons\Wechat\common\enums\WechatEnum;

/**
 * Class QrcodeStatService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class QrcodeStatService extends Service
{
    /**
     * 判断二维码扫描事件
     *
     * @param array $message 微信消息
     * @return bool|mixed
     */
    public function scan($message)
    {
        // 关注事件
        if ($message['Event'] == WechatEnum::EVENT_SUBSCRIBE && !empty($message['Ticket'])) {
            if ($qrCode = Yii::$app->wechatService->qrcode->findByWhere(['ticket' => trim($message['Ticket'])])) {
                $this->create($qrCode, $message['FromUserName'], QrcodeStatTypeEnum::ATTENTION);

                return $qrCode['keyword'];
            }
        }

        if (!isset($message['EventKey'])) {
            return false;
        }

        // 扫描事件
        $where = ['scene_str' => $message['EventKey']];
        if (is_numeric($message['EventKey'])) {
            $where = ['scene_id' => $message['EventKey']];
        }

        if ($qrCode = Yii::$app->wechatService->qrcode->findByWhere($where)) {
            Qrcode::updateAllCounters(['scan_num' => 1], ['id' => $qrCode['id']]);
            $this->create($qrCode, $message['FromUserName'], QrcodeStatTypeEnum::SCAN);

            // 触发绑定/登录
            if (
                !empty($qrCode['extend']) &&
                !empty($qrCode['extend']['type'])
            ) {
                $member = Yii::$app->services->member->findById($qrCode['extend']['member_id']);
                $remind = [
                    'time' => date('Y-m-d H:i:s'),
                    'member' => !empty($member) ? $member : []
                ];

                // 修改openid
                Qrcode::updateAll([
                    'extend' => ArrayHelper::merge($qrCode['extend'], [
                        'openid' => $message['FromUserName']
                    ])
                ], ['id' => $qrCode['id']]);

                switch ($qrCode['extend']['type']) {
                    // 绑定
                    case 'binding' :
                        if (
                            !empty($oldOauth = Yii::$app->services->memberAuth->findOauthClient(AccessTokenGroupEnum::WECHAT_MP, $message['FromUserName'], $member->type)) &&
                            !empty($oldMember = $oldOauth->member)
                        ) {
                            throw new Exception('绑定失败, 您已绑定账号 ' . $oldMember->username . ', 请先解绑', 200);
                        }

                        if (empty(Yii::$app->services->memberAuth->findByMemberIdOauthClient(AccessTokenGroupEnum::WECHAT_MP, $member->id))) {
                            Yii::$app->services->memberAuth->create([
                                'member_id' => $member->id,
                                'member_type' => $member->type,
                                'merchant_id' => $member->merchant_id,
                                'store_id' => $member->store_id,
                                'nickname' => $member->username,
                                'oauth_client' => AccessTokenGroupEnum::WECHAT_MP,
                                'oauth_client_user_id' => $message['FromUserName'],
                            ]);

                            throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['success'], $remind), 200);
                        }

                        throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['error'], $remind), 200);
                        break;
                    // 总后台登录
                    case 'login' :
                        $auth = Yii::$app->services->memberAuth->findOauthClient(AccessTokenGroupEnum::WECHAT_MP, $message['FromUserName'], MemberTypeEnum::MANAGER);
                        if ($auth) {
                            throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['success'], $remind), 200);
                        }

                        throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['error'], $remind), 200);
                        break;
                    // 总后台登录
                    case 'merchantLogin' :
                        $auth = Yii::$app->services->memberAuth->findOauthClient(AccessTokenGroupEnum::WECHAT_MP, $message['FromUserName'], MemberTypeEnum::MERCHANT);
                        if ($auth) {
                            throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['success'], $remind), 200);
                        }

                        throw new Exception(ArrayHelper::recursionGetVal($qrCode['extend']['remind']['error'], $remind), 200);
                        break;
                }
            }

            return $qrCode['keyword'];
        }

        return false;
    }

    /**
     * 插入扫描记录
     *
     * @param Qrcode $qrCode
     * @param $openid
     * @param $type
     */
    public function create($qrCode, $openid, $type)
    {
        $model = new QrcodeStat();
        $model->attributes = ArrayHelper::toArray($qrCode);
        $model->qrcord_id = $qrCode->id;
        $model->openid = $openid;
        $model->type = $type;
        !$model->save() && $this->error($model);
    }
}
