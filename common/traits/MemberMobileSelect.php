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
    public function actionMobileSelect($q = null, $id = null, $field = 'mobile')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [
            'results' => [
                'id' => '',
                'text' => ''
            ]
        ];

        $defaultCondition = ['like', 'mobile', $q];
        $condition = ['merchant_id' => Yii::$app->services->merchant->getNotNullId()];
        if (Yii::$app->services->devPattern->isB2B2C()) {
            $condition = [];
        }

        $field == 'id' && $defaultCondition = ['like', 'id', $q];
        $field == 'nickname' && $defaultCondition = ['like', 'nickname', $q];

        if (!is_null($q)) {
            $data = Member::find()
                ->select('id, nickname as text')
                ->where($defaultCondition)
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['type' => MemberTypeEnum::MEMBER])
                ->andFilterWhere($condition)
                ->limit(10)
                ->asArray()
                ->all();

            $out['results'] = array_values($data);
            array_unshift($out['results'], [
                'id' => 0,
                'text' => '不选择'
            ]);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Member::findOne($id)->mobile];
        }

        return $out;
    }
}
