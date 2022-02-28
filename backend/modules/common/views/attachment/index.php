<?php

use yii\grid\GridView;
use yii\helpers\Html as BaseHtml;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\DebrisHelper;
use common\helpers\Url;
use common\enums\AttachmentDriveEnum;
use common\enums\AttachmentUploadTypeEnum;

$this->title = '文件列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-12 col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['attachment/index']) ?>"> 资源文件</a></li>
                <li><a href="<?= Url::to(['attachment-cate/index']) ?>"> 资源分类</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
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
                                'attribute' => 'upload_type',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'upload_type', AttachmentUploadTypeEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control'
                                    ]
                                ),
                                'value' => function ($model) {
                                    return AttachmentUploadTypeEnum::getValue($model->upload_type);
                                },
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
                                'label' => '分类',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'cate_id', $cateMap, [
                                        'prompt' => '全部',
                                        'class' => 'form-control'
                                    ]
                                ),
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
                                        return Html::linkButton(['update', 'id' => $model->id], '编辑', [
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
                </div>
            </div>
        </div>
    </div>
</div>
