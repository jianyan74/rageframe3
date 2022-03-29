<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '管理员';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['create'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
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
                        [
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)),
                                    [
                                        'class' => 'img-circle rf-img-md elevation-1',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        'attribute' => 'username',
                        'realname',
                        'mobile',
                        [
                            'label' => '角色',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if (in_array($model->id, Yii::$app->params['adminAccount'])) {
                                    return Html::tag('span', '超级管理员', ['class' => 'label label-outline-success']);
                                } else {
                                    $str = [];
                                    foreach ($model->assignment as $value) {
                                        $str[] = Html::tag('span', $value->role->title, ['class' => 'label label-outline-primary']);
                                    }

                                    if (!empty($str)) {
                                        return implode(' ', $str);
                                    }
                                }

                                return Html::tag('span', '未授权', ['class' => 'label label-outline-default']);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '最后登录',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>' .
                                    "最后访问：" . Yii::$app->formatter->asDatetime($model->last_time) . '<br>' .
                                    "登录次数：" . $model->visit_count;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{role} {third-party} {update-password} {edit} {destroy}',
                            'contentOptions' => ['class' => 'text-align-center'],
                            'buttons' => [
                                'update-password' => function ($url, $model, $key) {
                                    return Html::a('修改密码', ['update-password', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'blue'
                                        ]) . '<br>';
                                },
                                'role' => function ($url, $model, $key) {
                                    if (
                                        in_array($model->id, Yii::$app->params['adminAccount']) ||
                                        Yii::$app->user->id == $model->id
                                    ) {
                                        return '';
                                    }

                                    return Html::a('角色授权', ['role', 'id' => $model->id], [
                                            'class' => 'cyan',
                                            'data-fancybox' => 'gallery',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]) . '<br>';
                                },
                                'third-party' => function ($url, $model, $key) {
                                    return Html::a('第三方授权', ['third-party/index', 'member_id' => $model->id], [
                                            'class' => 'cyan openIframeView',
                                        ]) . '<br>';
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::a('修改信息', ['edit', 'id' => $model->id], [
                                            'class' => 'purple openIframe',
                                        ]) . '<br>';
                                },
                                'destroy' => function ($url, $model, $key)  {
                                    if (!in_array($model->id, Yii::$app->params['adminAccount'])) {
                                        return Html::a('删除', ['destroy', 'id' => $model->id], [
                                            'class' => 'red',
                                        ]);
                                    }

                                    return '';
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
