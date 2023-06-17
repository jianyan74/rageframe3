<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\MemberHelper;

$this->title = '黑名单';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
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
                            'filter' => Html::activeTextInput($searchModel, 'id', [
                                    'class' => 'form-control',
                                    'style' => 'width: 50px'
                                ]
                            ),
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'headerOptions' => ['style' => 'width: 100px'],
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)), [
                                    'class' => 'img-circle rf-img-md elevation-1',
                                ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'nickname',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $tagsHtml = '';
                                $tagsTitle = [];
                                $i = 1;
                                foreach ($model->tag as $item) {
                                    $tagsTitle[]= Html::encode($item['title']);
                                    if ($i <= 3) {
                                        $tagsHtml .= '<span class="label label-outline-default">' . Html::encode($item['title']) . '</span>';
                                    }

                                    $i++;
                                }

                                $tagMore = count($model->tag) > 3 ? "<span title='" . implode(' ', $tagsTitle) ."'>...</span>" : '';

                                return Html::encode($model->nickname) . "<br>" . $tagsHtml . $tagMore;
                            },
                        ],
                        [
                            'attribute' => 'mobile',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'memberLevel.name',
                            'filter' => Html::activeDropDownList($searchModel, 'current_level', $levelMap, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model) {
                                return Html::tag('span', $model->memberLevel->name ?? '', [
                                    'class' => 'label label-outline-primary'
                                ]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '邀请人',
                            'attribute' => 'pid',
                            'headerOptions' => ['class' => 'col-md-1 text-align-center'],
                            'contentOptions' => ['class' => 'text-align-center'],
                            'filter' => Html::activeTextInput($searchModel, 'pid', [
                                    'class' => 'form-control',
                                    'placeholder' => '邀请用户 ID'
                                ]
                            ),
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->pid === 0 || empty($model->parent)) {
                                    return '---';
                                }

                                return MemberHelper::html($model->parent);
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
                            'template' => '{blacklist} {destroy}',
                            'buttons' => [
                                'blacklist' => function ($url, $model, $key) {
                                    return Html::a('移出黑名单', ['blacklist', 'id' => $model->id], [
                                            'class' => 'gray-dark',
                                            'onclick' => "rfTwiceAffirm(this, '确认移除黑名单吗？', '请谨慎操作');return false;"
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
