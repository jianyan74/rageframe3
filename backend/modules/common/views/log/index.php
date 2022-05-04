<?php

use yii\grid\GridView;
use common\enums\AppEnum;
use common\helpers\Html;
use common\helpers\Url;
use common\helpers\DebrisHelper;
use common\helpers\MemberHelper;
use kartik\daterange\DateRangePicker;

$this->title = '全局日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index']) ?>"> <?= $this->title; ?></a></li>
                <li><a href="<?= Url::to(['statistics']) ?>"> 数据统计</a></li>
                <li><a href="<?= Url::to(['ip-statistics']) ?>"> IP 统计</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        // 重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'method',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'app_id',
                                'filter' => Html::activeDropDownList($searchModel, 'app_id', AppEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]),
                                'value' => function ($model) {
                                    return AppEnum::getValue($model->app_id);
                                },
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            MemberHelper::gridView($searchModel),
                            'url',
                            [
                                'label' => '位置信息',
                                'attribute' => 'ip',
                                'value' => function ($model) {
                                    $str = [];
                                    $str[] = DebrisHelper::analysisIp($model->ip);
                                    $str[] = $model->ip;
                                    return implode("</br>", $str);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'error_code',
                                'label' => '状态码',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model) {
                                    if ($model->error_code < 300) {
                                        return '<span class="label label-outline-success">' . $model->error_code . '</span>';
                                    } else {
                                        return '<span class="label label-outline-danger">' . $model->error_code . '</span>';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => DateRangePicker::widget([
                                    'language' => 'zh-CN',
                                    'name' => 'queryDate',
                                    'value' => (!empty($startTime) && !empty($endTime)) ? ($startTime . '-' . $endTime) : '',
                                    'readonly' => 'readonly',
                                    'useWithAddon' => false,
                                    'convertFormat' => true,
                                    'startAttribute' => 'start_time',
                                    'endAttribute' => 'end_time',
                                    'startInputOptions' => ['value' => $startTime],
                                    'endInputOptions' => ['value' => $endTime],
                                    'presetDropdown' => true,
                                    'containerTemplate' => <<< HTML
        <div class="kv-drp-dropdown">
            <span class="left-ind">{pickerIcon}</span>
            <input type="text" readonly class="form-control range-value" value="{value}">
        </div>
        {input}
HTML,
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d H:i'],
                                        'timePicker' => true,
                                        'timePicker24Hour' => true,
                                        'timePickerIncrement' => 5
                                    ]
                                ]),
                                'value' => function ($model) {
                                    return date('Y-m-d H:i:s', $model['created_at']);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
                                            'class' => 'btn btn-white btn-sm openIframeView',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
