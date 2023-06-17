<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;
use common\enums\AttachmentUploadTypeEnum;

$this->title = '素材分组';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach (AttachmentUploadTypeEnum::getMap() as $key => $value) { ?>
                    <li class="<?php if ($type == $key) { ?>active<?php } ?>"><a href="<?= Url::to(['attachment/index', 'type' => $key])?>"><?= $value ?></a></li>
                <?php } ?>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'type' => $type], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="tab-pane active">
                        <nav class="nav-tabs-child">
                            <ul>
                                <li><a href="<?= Url::to(['attachment/index', 'type' => $type]) ?>">素材文件</a></li>
                                <li class="selected"><a href="<?= Url::to(['attachment-cate/index', 'type' => $type]) ?>">素材分组</a></li>
                            </ul>
                        </nav>
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
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
                                            'template' => '{edit} {status} {delete}',
                                            'buttons' => [
                                                'edit' => function ($url, $model, $key) {
                                                    return Html::edit(['ajax-edit', 'id' => $model->id, 'type' => $model->type], '编辑', [
                                                        'class' => 'btn btn-primary btn-sm',
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ajaxModal',
                                                    ]);
                                                },
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
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
