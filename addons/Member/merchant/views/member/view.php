<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;
use common\enums\GenderEnum;
use common\helpers\ImageHelper;
use common\helpers\DebrisHelper;
use common\enums\CreditsLogTypeEnum;
use common\enums\MemberStatusEnum;

$this->title = '个人资料';

?>

<style>
    .box-body .table {
        border: none;
        border-collapse: separate;
        border-spacing: 5px;
    }
    .box-body .table > thead > tr > th,
    .box-body .table > tbody > tr > th,
    .box-body .table > tfoot > tr > th,
    .box-body .table > thead > tr > td,
    .box-body .table > tbody > tr > td,
    .box-body .table > tfoot > tr > td {
        border-top: 0 solid #e4eaec;
        line-height: 1.42857;
        padding: 8px;
        vertical-align: middle;
    }

    .box-body .table tr td {
        padding: 4px 8px;
        height: 28px;
        line-height: 12px;
        border: none;
        text-align: left;
        padding-left: 10px;
        color: #000;
        font-size: 12px;
        border-radius: 4px;
        background: #f1f1f1;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <td rowspan="6" style="background: #ffffff;text-align: right;width: 200px">
                            <img src="<?= ImageHelper::defaultHeaderPortrait($member->head_portrait)?>" style="width: 200px">
                        </td>
                    </tr>
                    <tr>
                        <td>昵称：<?= Html::encode($member->nickname) ?></td>
                        <td>姓名：<?= Html::encode($member->realname) ?></td>
                        <td>账号：<?= Html::encode($member->username) ?></td>
                        <td>会员ID：<?= $member->id ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">可用余额：<?= $member->account->user_money ?? '' ?></td>
                        <td>可用积分：<?= $member->account->user_integral ?? '' ?></td>
                        <td>成长值：<?= $member->account->user_growth ?? '' ?></td>
                    </tr>
                    <tr>
                        <td>会员级别：<?= $member->memberLevel->name ?? '' ?></td>
                        <td>
                            生日：<?= $member->birthday ?>
                        </td>
                        <td>
                            性别：<?= GenderEnum::getValue($member->gender) ?>
                        </td>
                        <td>推广码：<?= $member['promoter_code'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">手机号码：<?= $member['mobile'] ?></td>
                        <td colspan="2">
                            推荐人：
                            <?php if ($member->parent) { ?>
                                <a href="<?= Url::toRoute(['/member/member/view', 'id' => $member->pid]) ?>" class="openIframeView blue"><?= Html::encode($member->parent->nickname) ?></a>
                            <?php } else { ?>
                                无
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            会员状态：<?= MemberStatusEnum::html($member['status']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>访问次数：<?= $member['visit_count'] ?></td>
                        <td>最近登录IP：<?= $member['last_ip'] ?></td>
                        <td>最近登录时间：<?= !empty($member['last_time']) ? Yii::$app->formatter->asDatetime($member['last_time']) : ''; ?></td>
                        <td colspan="2">最近登录地点：<?= DebrisHelper::analysisIp($member['last_ip']) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= $type == CreditsLogTypeEnum::USER_MONEY ? 'active' : ''; ?>"><a href="<?= Url::to(['view', 'id' => $id, 'type' => CreditsLogTypeEnum::USER_MONEY])?>"> 余额日志</a></li>
                <li class="<?= $type == CreditsLogTypeEnum::USER_INTEGRAL ? 'active' : ''; ?>"><a href="<?= Url::to(['view', 'id' => $id, 'type' => CreditsLogTypeEnum::USER_INTEGRAL])?>"> 积分日志</a></li>
                <li class="<?= $type == CreditsLogTypeEnum::USER_GROWTH ? 'active' : ''; ?>"><a href="<?= Url::to(['view', 'id' => $id, 'type' => CreditsLogTypeEnum::USER_GROWTH])?>"> 成长值日志</a></li>
                <li class="<?= $type == CreditsLogTypeEnum::CONSUME_MONEY ? 'active' : ''; ?>"><a href="<?= Url::to(['view', 'id' => $id, 'type' => CreditsLogTypeEnum::CONSUME_MONEY])?>"> 消费日志</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
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
                                'label' => '变动数量',
                                'attribute' => 'num',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-align-center'],
                                'contentOptions' => ['class' => 'text-align-center'],
                                'value' => function ($model) {
                                    return $model->num > 0 ? "<span class='green'>$model->num</span>" : "<span class='red'>$model->num</span>";
                                },
                            ],
                            [
                                'label' => '变动后数量',
                                'attribute' => 'new_num',
                                'headerOptions' => ['class' => 'text-align-center'],
                                'contentOptions' => ['class' => 'text-align-center'],
                                'filter' => Html::activeTextInput($searchModel, 'new_num', [
                                        'class' => 'form-control',
                                        'placeholder' => '变动后数量'
                                    ]
                                ),
                                'value' => function ($model) {
                                    // return $model->old_num . $operational . abs($model->num) . '=' . $model->new_num;
                                    return $model->new_num;
                                },
                            ],
                            'remark',
                            [
                                'attribute' => 'created_at',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

