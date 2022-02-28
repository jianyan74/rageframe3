<?php

use common\helpers\Url;
use common\helpers\Html;
use common\enums\WhetherEnum;
use jianyan\treegrid\TreeGrid;

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($cates as $cate) { ?>
                    <li class="<?php if ($cate['id'] == $cateId) {
                        echo 'active';
                    } ?>"><a href="<?= Url::to(['index', 'cate_id' => $cate['id']]) ?>"> <?= $cate['title'] ?></a></li>
                <?php } ?>
                <li><a href="<?= Url::to(['menu-cate/index']) ?>"> 菜单分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['edit', 'cate_id' => $cateId], '创建', [
                        'class' => 'openIframe',
                    ]); ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= TreeGrid::widget([
                        'dataProvider' => $dataProvider,
                        'keyColumnName' => 'id',
                        'parentColumnName' => 'pid',
                        'parentRootValue' => '0', //first parentId value
                        'pluginOptions' => [
                            'initialState' => 'collapsed',
                        ],
                        'options' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'attribute' => 'title',
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    $str = Html::tag('span', $model->title, [
                                        'class' => 'm-l-sm',
                                    ]);
                                    $str .= Html::a(' <i class="iconfont iconplus-circle"></i>',
                                        ['edit', 'pid' => $model['id'], 'cate_id' => $model['cate_id']], [
                                            'class' => 'openIframe',
                                        ]);

                                    return $str;
                                },
                            ],
                            'name',
                            'url',
                            [
                                'attribute' => 'icon',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column) {
                                    return Html::tag('span', '', [
                                        'class' => 'fa '.$model['icon'],
                                    ]);
                                },
                            ],
                            [
                                'attribute' => 'dev',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column) {
                                    return WhetherEnum::html($model['dev']);
                                },
                            ],
                            [
                                'attribute' => 'sort',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column) {
                                    return Html::sort($model->sort);
                                },
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['edit', 'id' => $model->id], '编辑', [
                                            'class' => 'btn btn-primary btn-sm openIframe',
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
                </div>
            </div>
        </div>
    </div>
</div>
