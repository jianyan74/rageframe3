<?php

namespace services\member;

use linslin\yii2\curl\Curl;
use common\enums\StatusEnum;
use common\models\member\Certification;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CertificationService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 *
 * 购买地址：https://market.aliyun.com/products/57124001/cmapi010401.html?#sku=yuncode440100000
 *
 */
class CertificationService
{
    const CODE = '';

    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMemberId($member_id)
    {
        return Certification::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * 解析返回
     *
     * @param string $identity_card_front 人面
     * @param string $identity_card_back 国徽
     */
    public function authentication($identity_card_front, $identity_card_back)
    {
        $front = $this->analysis($identity_card_front);
        $back = $this->analysis($identity_card_back, 'back');

        $model = new Certification();
        $model->loadDefaultValues();
        $model->realname = $front['name'];
        $model->address = $front['address'];
        $model->gender = $front['sex'];
        $model->nationality = $front['nationality'];
        $model->identity_card = $front['num'];
        $model->birthday = date('Y-m-d', strtotime($front['birth']));
        $model->front_is_fake = !empty($front['is_fake']) ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        // 国徽识别
        $model->issue = $back['issue'];
        $model->start_date = date('Y-m-d', strtotime($back['start_date']));
        $model->end_date = date('Y-m-d', strtotime($back['end_date']));
        $model->back_is_fake = !empty($back['is_fake']) ? StatusEnum::ENABLED : StatusEnum::DISABLED;
        $model->identity_card_front = $identity_card_front;
        $model->identity_card_back = $identity_card_back;

        return $model;
    }

    /**
     * @param $picture
     * @param string $side 身份证正反面类型:face(人面) / back(国徽)
     * @return mixed|null
     * @throws UnprocessableEntityHttpException
     */
    public function analysis($picture, $side = 'face')
    {
        $curl = new Curl();
        $result = $curl->setHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'APPCODE ' . self::CODE,
        ])->setRawPostData(Json::encode([
            'image' => $picture,
            'configure' => [
                'side' => $side
            ],
        ]))->post('http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json');

        $result = Json::decode($result);
        if ($result['success'] == true) {
            if ($side == "face" && strlen($result['num']) < 15) {
                throw new UnprocessableEntityHttpException('认证失败.');
            }

            return $result;
        }

        throw new UnprocessableEntityHttpException('认证失败.');
    }
}
