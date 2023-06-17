<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '广告图';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?> <small>默认使用有效期内的第一个图片，如果都是无效则使用默认图片</small></h3>
                <div class="box-tools">
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
                        ],
                        'name',
                        [
                            'attribute' => '有效时间',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                $str = [];
                                $str[] = '开始：' . Yii::$app->formatter->asDatetime($model->start_time);
                                $str[] = '结束：' . Yii::$app->formatter->asDatetime($model->end_time);

                                return implode('<br>', $str);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::timeStatus($model->start_time, $model->end_time);
                            },
                            'format' => 'raw',
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
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
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
