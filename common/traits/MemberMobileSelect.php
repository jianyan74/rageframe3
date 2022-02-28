<?php

namespace common\traits;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\enums\MemberTypeEnum;

/**
 * Trait MemberMobileSelect
 * @package common\traits
 */
trait MemberMobileSelect
{
    /**
     * select2 查询
     *
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionMobileSelect($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [
            'results' => [
                'id' => '',
                'text' => ''
            ]
        ];

        $condition = ['merchant_id' => Yii::$app->services->merchant->getNotNullId()];
        if (Yii::$app->services->devPattern->isB2B2C()) {
            $condition = [];
        }

        if (!is_null($q)) {
            $data = Member::find()
                ->select('id, mobile as text')
                ->where(['like', 'mobile', $q])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['type' => MemberTypeEnum::MEMBER])
                ->andFilterWhere($condition)
                ->limit(10)
                ->asArray()
                ->all();

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Member::findOne($id)->mobile];
        }

        return $out;
    }
}
