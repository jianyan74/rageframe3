<?php

use yii\grid\GridView;
use yii\helpers\Html as BaseHtml;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\DebrisHelper;
use common\helpers\Url;
use common\enums\AttachmentDriveEnum;
use common\enums\AttachmentUploadTypeEnum;

$this->title = '素材管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach (AttachmentUploadTypeEnum::getMap() as $key => $value) { ?>
                    <li class="<?php if ($type == $key) { ?>active<?php } ?>"><a href="<?= Url::to(['attachment/index', 'type' => $key])?>"><?= $value ?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="tab-pane active">
                        <nav class="nav-tabs-child">
                            <ul>
                                <li class="selected"><a href="<?= Url::to(['attachment/index', 'type' => $type]) ?>">素材文件</a></li>
                                <li><a href="<?= Url::to(['attachment-cate/index', 'type' => $type]) ?>">素材分组</a></li>
                            </ul>
                        </nav>
                        <div class="box">
                            <!-- /.box-header -->
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
                                            'attribute' => 'url',
                                            'filter' => false, //不显示搜索框
                                            'value' => function ($model) {
                                                if (($model['upload_type'] == 'images' || preg_match("/^image/", $model['specific_type'])) && $model['extension'] != 'psd') {
                                                    return ImageHelper::fancyBox($model->url);
                                                }

                                                return BaseHtml::a('预览', $model->url, [
                                                    'target' => '_blank'
                                                ]);
                                            },
                                            'format' => 'raw'
                                        ],
                                        [
                                            'attribute' => 'drive',
                                            'headerOptions' => ['class' => 'col-md-1'],
                                            'filter' => Html::activeDropDownList($searchModel, 'drive', AttachmentDriveEnum::getMap(), [
                                                    'prompt' => '全部',
                                                    'class' => 'form-control'
                                                ]
                                            ),
                                            'value' => function ($model) {
                                                return AttachmentDriveEnum::getValue($model->drive);
                                            },
                                        ],
                                        'name',
                                        [
                                            'attribute' => 'format_size',
                                            'filter' => false, //不显示搜索框
                                        ],
                                        [
                                            'attribute' => 'extension',
                                            'headerOptions' => ['class' => 'col-md-1'],
                                        ],
                                        [
                                            'attribute' => 'cate.title',
                                            'label' => '素材分组',
                                            'headerOptions' => ['class' => 'col-md-1'],
                                            'filter' => Html::activeDropDownList($searchModel, 'cate_id', $cateMap, [
                                                    'prompt' => '全部',
                                                    'class' => 'form-control'
                                                ]
                                            ),
                                            'value' => function ($model) {
                                                return $model->cate->title ?? '';
                                            },
                                        ],
                                        [
                                            'label' => '位置信息',
                                            'value' => function ($model) {
                                                $str = [];
                                                $str[] = DebrisHelper::analysisIp($model->ip);
                                                $str[] = $model->ip;
                                                return implode('</br>', $str);
                                            },
                                            'format' => 'raw',
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
                                            'template' => '{update} {status} {delete}',
                                            'buttons' => [
                                                'update' => function ($url, $model, $key) {
                                                    return Html::linkButton(['update', 'id' => $model->id, 'type' => $model->upload_type], '编辑', [
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ajaxModal',
                                                    ]);
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
            </div>
        </div>
    </div>
</div>
