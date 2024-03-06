<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\AddonHelper;
use common\enums\OfficialEnum;
use common\helpers\StringHelper;

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
                                'format' => 'raw',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) use ($newestVersion) {
                                    $str = $model->version;
                                    if (
                                            isset($newestVersion[$model->name]) &&
                                            StringHelper::strToInt($newestVersion[$model->name]) > StringHelper::strToInt($model->version)
                                    ) {
                                        $str .= ' <span class="label label-outline-warning">' . $newestVersion[$model->name] . '</span>';
                                    }

                                    return $str;
                                },
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{onLineUpgrade} {upgrade} {upgradeSql} {edit} {status} {delete}',
                                'buttons' => [
                                    'onLineUpgrade' => function ($url, $model, $key) {
                                        return Html::linkButton(['on-line-upgrade', 'name' => $model->name], '在线升级', [
                                            'data-name' => $model->name,
                                            'onclick' => "onLineUpgrade(this);return false;"
                                        ]);
                                    },
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

    function onLineUpgrade(that) {
        var href = $(that).attr('href');
        var name = $(that).data('name');
        var title = '确认在线升级吗?';
        var dialogText = '请注意先备份好服务器文件信息及数据库信息';

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
                    onLineUpgradeExecute(href, name);
                    break;
                default:
            }
        });
    }

    function onLineUpgradeExecute(href, name) {
        swal({
            title: '在线升级中...',
            text: '请不要关闭窗口',
            button: "确定",
        });

        $.ajax({
            type: "get",
            url: href,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal(
                        "更新配置中...",
                        "请不要关闭窗口，等待更新配置",
                    ).then((value) => {
                    });

                    var updateUrl = "<?= Url::to(['install', 'installData' => false])?>";
                    updateUrl += '&name=' + name;
                    $.ajax({
                        type: "get",
                        url: updateUrl,
                        dataType: "json",
                        success: function (data) {
                            if (parseInt(data.code) === 200) {
                                swal("升级成功", "小手一抖就打开了一个框", "success").then((value) => {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: '升级提示',
                                    text: data.message,
                                    button: "确定",
                                });
                            }
                        }
                    });
                } else {
                    setTimeout(function () {
                        swal({
                            title: '升级提示',
                            text: data.message,
                            button: "确定",
                        });
                    }, 1000)
                }
            }
        });
    }
</script>
