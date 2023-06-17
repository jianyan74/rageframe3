<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\WhetherEnum;
use common\enums\ExtendConfigNameEnum;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= $this->title; ?>
                </h3>
                <div class="box-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-white" data-toggle="dropdown" aria-expanded="false">立即创建</button>
                        <button type="button" class="btn btn-white dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                            <span class="sr-only">切换下拉</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right text-center" role="menu" style="">
                            <?php foreach ($nameMap as $key => $item){ ?>
                                <a class="dropdown-item p-xs" href="<?= Url::to(['edit', 'name' => $key])?>"><?= $item ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
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
                        'title',
                        [
                            'attribute' => 'name',
                            'label' => '标识',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeDropDownList($searchModel, 'name', $nameMap, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model, $key, $index, $column){
                                return ExtendConfigNameEnum::getValue($model->name);
                            }
                        ],
                        [
                            'attribute' => 'extend',
                            'label' => '自动打印',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => Html::activeDropDownList($searchModel, 'extend', WhetherEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model, $key, $index, $column){
                                return WhetherEnum::html($model->extend);
                            }
                        ],
                        [
                            'attribute' => 'sort',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model, $key, $index, $column){
                                return  Html::sort($model->sort);
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
                                    return Html::edit(['edit', 'id' => $model->id, 'name' => $model->name]);
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
