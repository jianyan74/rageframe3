<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::a('首页预览', Url::toFront(['index/index']), [
                        'class' => "btn btn-white btn-sm",
                        'target' => '_blank',
                    ]); ?>
                    <?= Html::create(['edit']); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'title',
                        'view',
                        [
                            'attribute' => 'cate_id',
                            'value' => 'cate.title',
                            'filter' => Html::activeDropDownList($searchModel, 'cate_id', Yii::$app->tinyBlogService->cate->getMapList(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        [
                            'attribute' => 'sort',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::sort($model->sort);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label'=> '创建时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        // 'updated_at',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{edit} {preview} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'preview' => function ($url, $model, $key) {
                                    return Html::a('预览', Url::toFront(['index/view', 'id' => $model->id, 'cate_id' => $model->cate_id]), [
                                        'class' => "btn btn-white btn-sm",
                                        'target' => '_blank',
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['hide', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>
