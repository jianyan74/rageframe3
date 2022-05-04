<?php

namespace addons\Wechat\services;

use Yii;
use Exception;
use common\helpers\ArrayHelper;
use common\components\Service;
use addons\Wechat\common\models\Qrcode;
use addons\Wechat\common\models\QrcodeStat;
use addons\Wechat\common\enums\QrcodeStatTypeEnum;
use addons\Wechat\common\enums\WechatEnum;
use common\enums\MemberAuthOauthClientEnum;
use yii\web\UnprocessableEntityHttpException;

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

            // 触发绑定
            if (
                !empty($qrCode['extend']) &&
                !empty($qrCode['extend']['member_id']) &&
                !empty($member = Yii::$app->services->member->findById($qrCode['extend']['member_id']))
            ) {
                if (empty(Yii::$app->services->memberAuth->findByMemberIdOauthClient(MemberAuthOauthClientEnum::WECHAT, $member->id))) {
                    Yii::$app->services->memberAuth->create([
                        'member_id' => $member->id,
                        'member_type' => $member->type,
                        'merchant_id' => $member->merchant_id,
                        'shop_id' => $member->shop_id,
                        'nickname' => $member->username,
                        'oauth_client' => MemberAuthOauthClientEnum::WECHAT,
                        'oauth_client_user_id' => $message['FromUserName'],
                    ]);

                    throw new Exception('账号 ' . $member->username . ' 绑定成功, 绑定时间: ' . date('Y-m-d H:i:s') . '0', 200);
                }

                throw new Exception('账号 ' . $member->username . ' 已被绑定过，请先解绑', 200);
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
