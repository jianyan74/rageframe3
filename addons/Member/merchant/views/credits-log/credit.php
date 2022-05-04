<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\MemberHelper;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
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
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
