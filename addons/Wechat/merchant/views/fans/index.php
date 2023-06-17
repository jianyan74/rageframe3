<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Auth;
use common\helpers\Html;
use common\helpers\ImageHelper;
use addons\Wechat\common\enums\FansSubscribeSceneEnum;
use addons\Wechat\common\enums\FansFollowEnum;

$this->title = '粉丝管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-2">
        <div class="box box-solid rfAddonMenu">
            <div class="box-header with-border pt-4 pl-3">
                <h3 class="rf-box-title">粉丝标签</h3>
            </div>
            <div class="box-body no-padding" style="padding-top: 0">
                <ul class="nav nav-pills nav-stacked">
                    <li class="nav-item">
                        <a href="<?= Url::to(['index']) ?>" class="nav-link"> 全部粉丝(<strong class="text-danger"><?= $fansCount ?></strong>)</a>
                        <?php foreach ($fansTags as $k => $tag) { ?>
                            <a href="<?= Url::to(['index', 'SearchModel[tags.tag_id]' => $tag['id']]) ?>" class="nav-link"> <?= $tag['name'] ?>(<strong class="text-danger"><?= $tag['count'] ?></strong>)</a>
                        <?php } ?>
                    </li>
                </ul>
                <div class="hr-line-dashed"></div>
            </div>
        </div>
    </div>
    <div class="col-10">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <!-- 权限校验判断 -->
                    <?php if (Auth::verify('/wechat/fans/sync')) { ?>
                        <span class="btn btn-white btn-sm" id="sync"><i class="fa fa-cloud-download"></i> 同步选中粉丝信息</span>
                    <?php } ?>
                    <span class="btn btn-white btn-sm" onclick="getAllFansInfo()"><i class="fa fa-cloud-download"></i>  同步没信息粉丝信息</span>
                    <!-- 权限校验判断 -->
                    <?php if (Auth::verify('/wechat/fans/get-all-fans')) { ?>
                        <span class="btn btn-white btn-sm" onclick="getAllFans()"><i class="fa fa-cloud-download"></i>  同步全部粉丝信息</span>
                    <?php } ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => [
                        'class' => 'table table-hover rf-table',
                        'fixedNumber' => 1,
                        'fixedRightNumber' => 1,
                    ],
                    'options' => [
                        'id' => 'grid',
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return ['value' => $model->openid];
                            },
                        ],
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->auth->head_portrait ?? '')),
                                    [
                                        'class' => 'img-circle rf-img-md elevation-1',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'nickname',
                            'value' => function ($model) {
                                return empty($model->auth) ? '未授权' : $model->auth->nickname;
                            },
                        ],
                        [
                            'attribute' => 'follow',
                            'label' => '关注状态',
                            'filter' => Html::activeDropDownList($searchModel, 'follow',
                                FansFollowEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model) {
                                return FansFollowEnum::getValue($model->follow);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'subscribe_scene',
                            'label' => '关注来源',
                            'filter' => Html::activeDropDownList($searchModel, 'subscribe_scene',
                                FansSubscribeSceneEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model) {
                                return FansSubscribeSceneEnum::getValue($model->subscribe_scene);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label' => '关注/取消时间',
                            'value' => function ($model) {
                                if ($model->follow == FansFollowEnum::ON) {
                                    return Yii::$app->formatter->asDatetime($model->follow_time);
                                }

                                return Yii::$app->formatter->asDatetime($model->unfollow_time);
                            },
                            'format' => 'raw'
                        ],
                        [
                            'label' => '标签',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model) use ($allTag) {
                                $str = [];
                                foreach ($model->tags as $tag) {
                                    $str[] = '<span class="label label-success">' . $allTag[$tag['tag_id']] . '</span>';
                                }

                                if (empty($str)) {
                                    $str[] = '<span class="label label-default">无标签</span>';
                                }

                                if (Auth::verify('/wechat/fans/move-tag')) {
                                    $str[] = '<a href=' . Url::to(['move-tag', 'fan_id' => $model->id]) . ' data-toggle="modal" data-target="#ajaxModal" style="color: #76838f">
                                                   <i class="icon ion-arrow-down-b "></i>
                                                   </a>';
                                }

                                return implode('', $str);
                            },
                            'format' => 'raw'
                        ],
                        'openid',
                        'remark',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{remark} {send-message}',
                            'headerOptions' => ['class' => 'col-md-2'],
                            'buttons' => [
                                'remark' => function ($url, $model, $key) {
                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '备注', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'send-message' => function ($url, $model, $key) {
                                    return Html::linkButton(['send-message', 'openid' => $model->openid],
                                        '发送消息', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
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

<script>
    var num = 1;

    // 同步所有粉丝openid
    function getAllFans() {
        rfAffirm('同步中,请不要关闭当前页面');
        num = 1;
        syncOpenid();
    }

    function syncOpenid(next_openid = '') {
        $.ajax({
            type: "get",
            url: "<?= Url::to(['sync-all-openid'])?>",
            dataType: "json",
            data: {next_openid: next_openid},
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    if (data.data.next_openid) {
                        syncOpenid(data.data.next_openid);
                    } else {
                        sync('all');
                    }
                } else {
                    rfAffirm(data.message);
                    window.location.reload();
                }
            }
        });
    }

    // 同步粉丝资料
    function sync(type, page = 0, openids = null) {
        $.ajax({
            type: "post",
            url: "<?= Url::to(['sync'])?>",
            dataType: "json",
            data: {type: type, page: page, openids: openids},
            success: function (data) {
                if (parseInt(data.code) === 200 && data.data.page) {
                    sync(type, data.data.page);
                    $('#syncPage').html('同步第 ' + num + ' 次');
                    num++;
                } else {
                    rfAffirm(data.message);
                    window.location.reload();
                }
            }
        });
    }

    function getAllFansInfo() {
        num = 1;
        rfAffirm('同步中,请不要关闭当前页面');
        syncInfo()
    }

    function syncInfo() {
        $.ajax({
            type: "post",
            url: "<?= Url::to(['sync-info'])?>",
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    syncInfo();
                    $('#syncPage').html('同步第 ' + num + ' 次');
                    num++;
                } else {
                    rfAffirm(data.message);
                    window.location.reload();
                }
            }
        });
    }

    // 同步选中的粉丝
    $("#sync").click(function () {
        var openids = [];
        $("#grid :checkbox").each(function () {
            if (this.checked) {
                var openid = $(this).val();
                if (openid !== "1") {
                    openids.push(openid);
                }
            }
        });

        sync('check', 0, openids);
    });
</script>
