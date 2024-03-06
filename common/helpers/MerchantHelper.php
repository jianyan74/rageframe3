<?php

namespace common\helpers;

use yii\web\JsExpression;
use common\models\merchant\Merchant;

/**
 * Class MerchantHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantHelper
{
    const ONE = 'one';
    const TWO = 'two';

    /**
     * @param Merchant $merchant
     * @return string
     */
    public static function html($merchant)
    {
        if (empty($merchant)) {
            return '';
        }

        $url = Url::toRoute(['/merchants/merchant/view', 'id' => $merchant->id]);
        $url = '#';
        $name = '商户名: ' . Html::encode($merchant['title']);
        $hideName = Html::encode(StringHelper::textNewLine($merchant->title, 6, 1)[0]);
        $cover = Html::img(ImageHelper::defaultHeaderPortrait($merchant->cover), [
            'class' => 'img-circle elevation-1',
            'width' => '35',
            'height' => '35',
        ]);

        $toggle = [];
        $toggle[] = '商户ID: ' . $merchant->id;
        $toggle[] = $name;
        $toggle[] = '联系方式: ' . (!empty($merchant['mobile']) ? StringHelper::hideStr($merchant['mobile'], 3, 4) : '-');
        $toggle = "<div class='text-left'>" . implode('<br>', $toggle) ."</div>";

        return '<div class="text-center" href="' . $url . '">' . $cover . '<a class="users-list-name pt-1" data-toggle="tooltip" data-placement="bottom" data-html="true" title="' . $toggle . '" href="javascript: void(0)">' . $hideName . '</a></div>';
    }

    /**
     * @param $searchModel
     * @param $model
     * @return array
     */
    public static function gridView($searchModel, $label = '所属商户', $relevancy = 'merchant', $default = '无')
    {
        return [
            'label' => $label,
            'attribute' => 'merchant_id',
            'headerOptions' => ['class' => 'col-md-1 text-align-center'],
            'contentOptions' => ['class' => 'text-align-center'],
            'filter' => \kartik\select2\Select2::widget([
                'name' => 'SearchModel[merchant_id]',
                'initValueText' => '', // set the initial display text
                'options' => ['placeholder' => '请输入店铺名称'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return '等待中...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['/merchant/select2']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { 
                                return {q:params.term}; 
                        }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ]),
            'value' => function ($model) use ($relevancy, $default) {
                if (empty($model->$relevancy)) {
                    return $default;
                }

                return self::html($model->$relevancy);
            },
            'format' => 'raw',
        ];
    }
}
