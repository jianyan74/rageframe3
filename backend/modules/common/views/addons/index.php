<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\AddonHelper;
use common\enums\OfficialEnum;

$this->title = '已安装的插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index']) ?>">已安装的插件</a></li>
                <li><a href="<?= Url::to(['local']) ?>">安装插件</a></li>
                <li><a href="<?= Url::to(['create']) ?>">设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        // 重新定义分页样式
                        'tableOptions' => [
                            'class' => 'table table-hover rf-table',
                            'fixedNumber' => 2,
                            'fixedRightNumber' => 1,
                        ],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                            ],
                            [
                                'attribute' => 'icon',
                                'label' => '图标',
                                'filter' => false, //不显示搜索框
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model) {
                                    if ($path = AddonHelper::getAddonIcon($model['name'])) {
                                        return Html::img($path, [
                                            'class' => 'img-rounded m-t-xs img-responsive',
                                            'width' => '64',
                                            'height' => '64',
                                        ]);
                                    }

                                    return '<span class="iconfont iconchajian blue" style="font-size: 50px"></span>';
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'title',
                                // 'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    $str = '<h4> ' . $model['title'] . '</h4>';
                                    $str .= "<small>" . $model['name'] . "</small>";
                                    return $str;
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'author',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'label' => '组别',
                                'attribute' => 'group',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) use ($addonsGroup) {
                                    return '<span class="label label-outline-primary">' . $addonsGroup[$model->group]['title'] . '</span> ';
                                },
                                'format' => 'raw'
                            ],
                            [
                                'label' => '功能支持',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    $str = '';
                                    $model['is_merchant_route_map'] == true && $str .= '<span class="label label-outline-info">商户路由映射</span>';
                                    return $str;
                                },
                                'format' => 'raw'
                            ],
                            [
                                'attribute' => 'brief_introduction',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'attribute' => 'version',
                                'filter' => false, //不显示搜索框
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{upgrade} {upgradeSql} {edit} {status} {delete}',
                                'buttons' => [
                                    'upgrade' => function ($url, $model, $key) {
                                        return Html::linkButton(['install', 'name' => $model->name, 'installData' => false], '更新配置', [
                                                'onclick' => "upgrade(this);return false;"
                                        ]);
                                    },
                                    'upgradeSql' => function ($url, $model, $key) {
                                        return Html::linkButton(['upgrade', 'name' => $model->name], '数据库升级', [
                                            'onclick' => "upgradeSqlAffirm(this);return false;",
                                        ]);
                                    },
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function ($url, $model, $key) {
                                        if ($model->name == OfficialEnum::AUTHORITY) {
                                            return false;
                                        }

                                        return Html::status($model->status);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        if ($model->name == OfficialEnum::AUTHORITY) {
                                            return false;
                                        }

                                        return Html::linkButton(['un-install', 'name' => $model->name], '卸载', [
                                            'class' => 'btn btn-danger btn-sm',
                                            'onclick' => "rfTwiceAffirm(this, '确认卸载插件么？', '请谨慎操作');return false;",
                                        ]);
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

<script>
    function upgradeSqlAffirm(that) {
        var title = '确认升级数据库吗?';
        var dialogText = '会执行更新数据库字段升级等功能';
        var href = $(that).attr('href');
        swal(title, {
            buttons: {
                cancel: "取消",
                defeat: '确定'
            },
            title: title,
            text: dialogText,
            // icon: "warning",
        }).then(function (value) {
            switch (value) {
                case "defeat":
                    upgradeSql(href);
                    break;
                default:
            }
        });

        return false;
    }

    function upgradeSql(href) {
        swal({
            title: '升级中...',
            text: '请不要关闭窗口，等待升级',
            button: "确定",
        });

        $.ajax({
            type: "get",
            url: href,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal("数据库升级成功", "小手一抖就打开了一个框", "success").then((value) => {
                        location.reload();
                    });
                } else {
                    rfMsg(data.message);
                }
            }
        });
    }

    function upgrade(that) {
        var href = $(that).attr('href');

        swal({
            title: '更新配置中...',
            text: '请不要关闭窗口，等待更新配置',
            button: "确定",
        });

        $.ajax({
            type: "get",
            url: href,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal("更新配置成功", "小手一抖就打开了一个框", "success").then((value) => {
                        location.reload();
                    });
                } else {
                    rfMsg(data.message);
                }
            }
        });
    }
</script>
