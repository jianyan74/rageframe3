<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;
use common\enums\AuditStatusEnum;
use common\helpers\MemberHelper;

$this->title = '会员注销';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['cancel/index'])?>">会员注销(<?= Yii::$app->services->memberCancel->getApplyCount(); ?>)</a></li>
                <li><a href="<?= Url::to(['setting/config'])?>">注销设置</a></li>
                <li class="hide"><a href="<?= Url::to(['setting/display'])?>">注销协议</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="box">
                        <!-- /.box-header -->
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
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                    ],
                                    MemberHelper::gridView($searchModel),
                                    [
                                        'attribute' => 'audit_status',
                                        'format' => 'raw',
                                        'filter' => Html::activeDropDownList($searchModel, 'audit_status', AuditStatusEnum::getMap(), [
                                                'prompt' => '全部',
                                                'class' => 'form-control'
                                            ]
                                        ),
                                        'value' => function ($model) {
                                            return AuditStatusEnum::html($model->audit_status);
                                        },
                                    ],
                                    'refusal_cause',
                                    [
                                        'label' => '申请时间',
                                        'attribute' => 'created_at',
                                        'filter' => false, //不显示搜索框
                                        'format' => ['date', 'php:Y-m-d H:i'],
                                    ],
                                    [
                                        'header' => "操作",
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{pass} {refuse}',
                                        'buttons' => [
                                            'pass' => function ($url, $model, $key) {
                                                if ($model->audit_status == AuditStatusEnum::DISABLED) {
                                                    return Html::a('通过', ['pass', 'id' => $model->id], [
                                                        'class' => 'green',
                                                        'onclick' => "rfTwiceAffirm(this, '确认通过申请吗？');return false;",
                                                    ]) . ' | ';
                                                }
                                            },
                                            'refuse' => function ($url, $model, $key) {
                                                if ($model->audit_status == AuditStatusEnum::DISABLED) {
                                                    return Html::a('拒绝', ['refuse', 'id' => $model->id], [
                                                        'class' => 'red',
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ajaxModal',
                                                    ]);
                                                }
                                            }
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
