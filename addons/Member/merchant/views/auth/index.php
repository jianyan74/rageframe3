<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\GenderEnum;
use common\helpers\ImageHelper;
use common\helpers\MemberHelper;
use common\enums\AccessTokenGroupEnum;

$this->title = '第三方授权';
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
                        'nickname',
                        [
                            'attribute' => 'gender',
                            'value' => function ($model, $key, $index, $column) {
                                return GenderEnum::getValue($model->gender);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'gender', GenderEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        [
                            'attribute' => 'oauth_client',
                            'value' => function ($model, $key, $index, $column) {
                                return AccessTokenGroupEnum::getValue($model->oauth_client);
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'oauth_client', AccessTokenGroupEnum::getThirdPartyMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        'oauth_client_user_id',
                        MemberHelper::gridView($searchModel, '关联用户'),
                        [
                            'attribute' => 'province',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'city',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
