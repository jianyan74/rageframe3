<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\MemberHelper;
use kartik\daterange\DateRangePicker;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => [$action]];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::export(['export', 'type' => $type], '导出', [
                        'title' => '根据搜索结果导出表格',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    // 重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        MemberHelper::gridView($searchModel),
                        [
                            'label' => '变动数量',
                            'attribute' => 'num',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'text-align-center'],
                            'contentOptions' => ['class' => 'text-align-center'],
                            'value' => function ($model) {
                                return $model->num > 0 ? "<span class='green'>$model->num</span>" : "<span class='red'>$model->num</span>";
                            },
                        ],
                        [
                            'label' => '变动后数量',
                            'attribute' => 'new_num',
                            'headerOptions' => ['class' => 'text-align-center'],
                            'contentOptions' => ['class' => 'text-align-center'],
                            'filter' => Html::activeTextInput($searchModel, 'new_num', [
                                    'class' => 'form-control',
                                    'placeholder' => '变动后数量'
                                ]
                            ),
                            'value' => function ($model) {
                                // return $model->old_num . $operational . abs($model->num) . '=' . $model->new_num;
                                return $model->new_num;
                            },
                        ],
                        'remark',
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
                                    'locale' => ['format' => 'Y-m-d H:i:s'],
                                    'timePicker' => true,
                                    'timePicker24Hour' => true,
                                    'timePickerSeconds' => true,
                                    'timePickerIncrement' => 1
                                ]
                            ]),
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
