<?php

use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ArrayHelper;
use common\helpers\Html;
use common\helpers\ImageHelper;
use yii\web\JsExpression;
use kartik\select2\Select2;

$this->title = '会员信息';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    // 重新定义分页样式
                    'tableOptions' => [
                        'class' => 'table table-hover rf-table',
                        'fixedNumber' => 2,
                        'fixedRightNumber' => 1,
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'attribute' => 'id',
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'headerOptions' => ['style' => 'width: 100px'],
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)),
                                    [
                                        'class' => 'img-circle rf-img-md elevation-1',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'nickname',
                            'value' => function ($model) {
                                $html = $model->nickname;

                                return $html;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'mobile',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'memberLevel.name',
                            'value' => function ($model) {
                                return Html::tag('span', $model->memberLevel->name ?? '', [
                                    'class' => 'label label-outline-primary'
                                ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'label' => '上级会员',
                            'attribute' => 'pid',
                            'filter' => Select2::widget([
                                'name' => 'SearchModel[pid]',
                                'initValueText' => '', // set the initial display text
                                'options' => ['placeholder' => '手机号码查询'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return '等待中...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['mobile-select']),
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
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->pid === 0) {
                                    return '---';
                                }

                                $str = [];
                                $str[] = 'ID：' . $model->parent->id ?? '';
                                $str[] = '姓名：' . Html::encode($model->parent->nickname ?? '');
                                $str[] = '昵称：' . Html::encode($model->parent->nickname ?? '');
                                $str[] = '手机：' . Html::encode($model->parent->mobile ?? '');

                                return implode('<br>', $str);
                            },
                        ],
                        [
                            'label' => '账户金额',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->account->user_money . '<br>' .
                                    "累计：" . $model->account->accumulate_money . '<br>' .
                                    "累计消费：" . abs($model->account->consume_money);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '账户积分',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->account->user_integral . '<br>' .
                                    "累计：" . $model->account->accumulate_integral . '<br>' .
                                    "累计消费：" . abs($model->account->consume_integral);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '账户成长值',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "当前：" . $model->account->user_growth . '<br>' .
                                    "累计：" . $model->account->accumulate_growth . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '最后登录',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>' .
                                    "最后访问：" . (!empty($model->last_time) ? Yii::$app->formatter->asDatetime($model->last_time) : '---') . '<br>' .
                                    "登录次数：" . $model->visit_count . '<br>' .
                                    "注册时间：" . Yii::$app->formatter->asDatetime($model->created_at) . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'contentOptions' => ['class' => 'text-align-center'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{ajax-edit} {address} {update-level} {recharge} {edit} {status} {destroy}',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::a('账号密码', ['ajax-edit', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'blue'
                                        ]) . '<br>';
                                },
                                'address' => function ($url, $model, $key) {
                                    return Html::a('收货地址', ['address/index', 'member_id' => $model->id], [
                                            'class' => 'cyan'
                                        ]) . '<br>';
                                },
                                'update-level' => function ($url, $model, $key) {
                                    return Html::a('修改等级', ['update-level', 'id' => $model->id], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                        'class' => 'green'
                                    ]) . '<br>';
                                },
                                'recharge' => function ($url, $model, $key) {
                                    return Html::a('充值', ['recharge', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'orange'
                                        ]) . '<br>';
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::a('编辑', ['edit', 'id' => $model->id], [
                                            'class' => 'purple'
                                        ]) . '<br>';
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::a('删除', ['destroy', 'id' => $model->id], [
                                            'class' => 'red',
                                            'onclick' => "rfTwiceAffirm(this, '确认删除吗？', '请谨慎操作');return false;"
                                        ]) . '<br>';
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
