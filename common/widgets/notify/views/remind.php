<?php

use yii\grid\GridView;
use yii\helpers\Url;
use common\helpers\Html;
use common\enums\NotifyTypeEnum;

if (empty($type)) {
    $this->title = '全部消息';
} else {
    $this->title = NotifyTypeEnum::getValue($type) . '列表';
}

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-sm-2">
        <div class="box box-solid rfAddonMenu">
            <div class="box-header with-border pt-4 pl-3">
                <h3 class="rf-box-title">消息提醒</h3>
            </div>
            <div class="box-body no-padding" style="padding-top: 0">
                <?= $this->render('_nav') ?>
                <div class="hr-line-dashed"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::a('全部已读', ['read-all', 'type' => $type], [
                        'onclick' => "rfTwiceAffirm(this, '确认全部设为已读么？', '可能会漏看一些关键信息，请谨慎操作');return false;"
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-sm-12 m-b-sm m-l">
                    <?= Html::a('批量已读', "javascript:void(0);", ['class' => 'btn btn-white btn-sm m-l-n-md read']); ?>
                    <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-white btn-sm delete hide']); ?>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'options' => [
                        'id' => 'grid'
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return [
                                    'value' => $model->id,
                                ];
                            }
                        ],
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => true, // 不显示#
                        ],
                        'notify.title',
                        [
                            'label' => '消息内容',
                            'attribute' => 'notify.content',
                            'format' => 'raw',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if ($model->type == NotifyTypeEnum::ANNOUNCE) {
                                    return '内容过多，请查看详情';
                                }

                                $str = $model->notify->content ?? '';
                                $model->notify->target_id> 0 && $str .= ' <small style="color: #999999">#' . $model->notify->target_id . '</small>';

                                return $str;
                            },
                        ],
                        [
                            'label' => '创建时间',
                            'attribute' => 'created_at',
                            'headerOptions' => ['class' => 'col-md-2'],
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'label' => '查看时间',
                            'attribute' => 'updated_at',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if (empty($model['is_read'])) {
                                    return '未读';
                                }

                                return Yii::$app->formatter->asRelativeTime($model['updated_at']);
                            },
                        ],
                        [
                            'label' => '操作',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model) {
                                switch ($model->type) {
                                    case NotifyTypeEnum::ANNOUNCE :
                                        return Html::a('查看详情', ['announce-view', 'id' => $model->id], [
                                            'class' => 'blue',
                                        ]);
                                    default :
                                        if (!empty($model->notify->link)) {
                                            return Html::a('查看详情', [$model->notify->link], [
                                                'class' => 'blue openIframeView',
                                            ]);
                                        }
                                        break;
                                }

                                return '';
                            }
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(".read").on("click", function () {
        var ids = $("#grid").yiiGridView("getSelectedRows");

        $.ajax({
            type: "post",
            url: "<?= Url::to(['read'])?>",
            dataType: "json",
            data: {ids: ids},
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal('小手一抖打开一个窗', {
                        buttons: {
                            defeat: '确定',
                        },
                        title: '操作成功',
                    }).then(function (value) {
                        switch (value) {
                            case "defeat":
                                location.reload();
                                break;
                            default:
                        }
                    });
                } else {
                    rfMsg(data.message);
                }
            }
        });
    });

    $(".delete").on("click", function () {
        var ids = $("#grid").yiiGridView("getSelectedRows");

        $.ajax({
            type: "post",
            url: "<?= Url::to(['delete-all'])?>",
            dataType: "json",
            data: {ids: ids},
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal('小手一抖打开一个窗', {
                        buttons: {
                            defeat: '确定',
                        },
                        title: '操作成功',
                    }).then(function (value) {
                        switch (value) {
                            case "defeat":
                                location.reload();
                                break;
                            default:
                        }
                    });
                } else {
                    rfMsg(data.message);
                }
            }
        });
    });
</script>