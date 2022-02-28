<?php

use yii\grid\GridView;
use yii\helpers\Html as BaseHtml;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\DebrisHelper;
use common\helpers\Url;

$this->title = '文件列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['attachment/index']) ?>"> 资源文件</a></li>
                <li class="active"><a href="<?= Url::to(['attachment-cate/index']) ?>"> 资源分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                            ],
                            'title',
                            [
                                'attribute' => 'sort',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column) {
                                    return Html::sort($model->sort);
                                }
                            ],
                            [
                                'label' => '创建时间',
                                'attribute' => 'created_at',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{status} {delete}',
                                'buttons' => [
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::delete(['delete', 'id' => $model->id]);
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