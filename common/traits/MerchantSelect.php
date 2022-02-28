<?php

namespace common\traits;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\merchant\Merchant;

/**
 * Trait MerchantSelectAction
 * @package common\traits
 */
trait MerchantSelect
{
    /**
     * select2 查询
     *
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionSelect2($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [
            'results' => [
                'id' => '',
                'text' => ''
            ]
        ];

        if (!is_null($q)) {
            $data = Merchant::find()
                ->select('id, title as text')
                ->where(['like', 'title', $q])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->limit(10)
                ->asArray()
                ->all();

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Merchant::findOne($id)->mobile];
        }

        return $out;
    }
}
