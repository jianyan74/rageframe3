<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;
use addons\Wechat\common\enums\QrcodeModelTypeEnum;

$this->title = '二维码管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];

?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index'])?>"> 二维码管理</a></li>
                <li><a href="<?= Url::to(['qrcode-stat/index'])?>"> 扫描统计</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ])?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        //重新定义分页样式
                        'tableOptions' => [
                            'class' => 'table table-hover rf-table',
                            'fixedNumber' => 1,
                            'fixedRightNumber' => 1,
                        ],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                            ],
                            [
                                'label'=> '二维码',
                                'value' => function ($model) {
                                    return '<a href="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $model->ticket . '" data-fancybox="gallery">
                                        <img src="' . Url::to(['qr', 'shortUrl' => Yii::$app->request->hostInfo]) . '" alt="" width="45">
                                    </a>';
                                },
                                'format' => 'raw'
                            ],
                            'name',
                            [
                                'attribute' => 'keyword',
                                'value' => function ($model) {
                                    return Html::a($model->keyword, ['qrcode-stat/index', 'keyword' => $model->name]);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'attribute' => 'model_type',
                                'filter' => Html::activeDropDownList($searchModel, 'model_type', QrcodeModelTypeEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control'
                                    ]
                                ),
                                'value' => function ($model) {
                                    return QrcodeModelTypeEnum::getValue($model->model_type);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label'=> '场景ID/场景字符串',
                                'value' => function ($model) {
                                    return $model->model_type == QrcodeModelTypeEnum::TEM ? $model->scene_id : $model->scene_str;
                                },
                                'format' => 'raw'
                            ],
                            [
                                'label'=> '有效期',
                                'value' => function ($model) {
                                    $str = [];
                                    $str[] = '开始: ' . Yii::$app->formatter->asDatetime($model->created_at);
                                    if ($model->model_type == QrcodeModelTypeEnum::TEM) {
                                        $str[] = '结束: ' . Yii::$app->formatter->asDatetime($model->end_time);
                                    } else {
                                        $str[] = '<span class="green">永不</span>';
                                    }

                                    if ($model->model_type == QrcodeModelTypeEnum::TEM) {
                                        $str[] = $model->end_time < time() ? "<span class='red'>已过期</span>" : "未过期";
                                    }

                                    return implode('<br>', $str);
                                },
                                'format' => 'raw'
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{down} {edit} {delete}',
                                'buttons' => [
                                    'down' => function ($url, $model, $key) {
                                        return Html::linkButton(['down', 'id' => $model->id], '下载');
                                    },
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        if ($model->model_type == QrcodeModelTypeEnum::PERPETUAL) {
                                            return Html::delete(['delete', 'id' => $model->id]);
                                        }

                                        return '';
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                    <div class="col-12">
                        <?= Html::linkButton(['delete-all'], '删除过期二维码', [
                            'class' => 'btn btn-warning btn-sm'
                        ]); ?>
                        <span>
                            注意：永久二维码无法在微信平台删除，但是您可以点击
                            <a href="javascript:void(0);" class="color-default">【删除】</a>来删除本地数据。
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
