<?php

use addons\WechatMini\common\enums\live\LiveStatusEnum;
use common\enums\WhetherEnum;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\helpers\Url;
use yii\grid\GridView;

$this->title = '直播间';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <span class="btn btn-primary btn-sm sync">同步</span>
                    <?= Html::create(['edit']); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => [
                        'class' => 'table table-hover rf-table',
                        'fixedNumber' => 2,
                        'fixedRightNumber' => 1,
                    ],
                    'options' => [
                        'id' => 'grid',
                    ],
                    'columns' => [
                        'roomid',
                        'name',
                        'anchor_name',
                        [
                            'attribute' => 'cover_img',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if (!empty($model->cover_img)) {
                                    return ImageHelper::fancyBox($model->cover_img);
                                }
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'share_img',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if (!empty($model->share_img)) {
                                    return ImageHelper::fancyBox($model->share_img);
                                }
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label' => '直播状态(仅供参考)',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->live_status > LiveStatusEnum::END) {
                                    return LiveStatusEnum::getValue($model->live_status);
                                }

                                return  '正常';
                            },
                        ],
                        [
                            'label' => '直播时间',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $html = '';
                                $html .= '开始时间：' . Yii::$app->formatter->asDatetime($model->start_time) . "<br>";
                                $html .= '结束时间：' . Yii::$app->formatter->asDatetime($model->end_time) . "<br>";
                                $html .= '有效状态：' . Html::timeStatus($model->start_time, $model->end_time);

                                return $html;
                            },
                        ],
                        [
                            'label' => '推荐?',
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'is_recommend', WhetherEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]
                            ),
                            'value' => function ($model) {
                                return WhetherEnum::getValue($model->is_recommend);
                            },
                        ],
                        [
                            'label' => '置顶?',
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'is_stick', WhetherEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]
                            ),
                            'value' => function ($model) {
                                return WhetherEnum::getValue($model->is_stick);
                            },
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
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

<script>
    // 获取资源
    $(".sync").click(function () {
        rfAffirm('同步中,请不要关闭当前页面');
        sync();
    });

    // 正式同步
    function sync(offset = 0, count = 10, clear = 1) {
        $.ajax({
            type: "get",
            url: "<?= Url::to(['sync'])?>",
            dataType: "json",
            data: {offset: offset, count: count, clear:clear},
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    sync(data.data.offset, data.data.count, 0);
                } else if (parseInt(data.code) === 201) {
                    rfAffirm(data.message);
                    window.location.reload();
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
