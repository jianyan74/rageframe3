<?php

namespace common\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\helpers\Html;

/**
 * Class ProvincesController
 * @package common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ProvincesController extends Controller
{
    /**
     * 联动查询返回
     */
    public function actionChild($pid, $type_id = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Yii::$app->services->provinces->getCityMapByPid($pid);
        switch ($type_id) {
            case 1:
                $str = Html::tag('option', '请选择市', ['value' => '']);
                break;
            case 2:
                $str = Html::tag('option', '请选择区', ['value' => '']);
                break;
            case 3:
                $str = Html::tag('option', '请选择乡/镇', ['value' => '']);
                break;
            case 4:
                $str = Html::tag('option', '请选择村/社区', ['value' => '']);
                break;
        }

        if (!$pid) {
            return $str;
        }

        foreach ($model as $value => $name) {
            $str .= Html::tag('option', Html::encode($name), ['value' => $value]);
        }

        return $str;
    }
}
