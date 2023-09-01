<?php

namespace api\modules\v1\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\enums\PayTypeEnum;
use common\helpers\Url;
use common\forms\PayForm;
use common\helpers\ResultHelper;
use common\forms\OrderPayFrom;
use common\forms\RechargePayFrom;

/**
 * 公用支付生成
 *
 * Class PayController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PayController extends OnAuthController
{
    /**
     * @var PayForm
     */
    public $modelClass = PayForm::class;

    /**
     * 生成支付参数
     *
     * @return array|bool|mixed|\yii\db\ActiveRecord
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        /* @var $payForm PayForm */
        $payForm = new $this->modelClass();
        $payForm->attributes = Yii::$app->request->post();
        $payForm->member_id = Yii::$app->user->identity->member_id;
        $payForm->code = Yii::$app->request->get('code');
        if (!$payForm->validate()) {
            return ResultHelper::json(422, $this->getError($payForm));
        }

        // 非余额支付
        if ($payForm->pay_type != PayTypeEnum::USER_MONEY) {
            // 执行方法
            $payForm->setHandlers([
                'recharge' => RechargePayFrom::class,
                'order' => OrderPayFrom::class,
            ]);
            // 回调方法
            $payForm->notify_url = Url::removeMerchantIdUrl('toApi', ['notify/' . PayTypeEnum::action($payForm->pay_type)]);

            // 生成配置
            return ResultHelper::json(200, '待支付', [
                'payStatus' => false,
                'config' => $payForm->getConfig(),
            ]);
        }
    }
}
