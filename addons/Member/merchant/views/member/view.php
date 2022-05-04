<?php

use common\helpers\Url;
use common\helpers\Html;
use common\enums\GenderEnum;
use common\helpers\ImageHelper;
use common\helpers\DebrisHelper;

?>

<style>
    .table {
        border: none;
        border-collapse: separate;
        border-spacing: 5px;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
        border-top: 0 solid #e4eaec;
        line-height: 1.42857;
        padding: 8px;
        vertical-align: middle;
    }

    .table tr td {
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
                        <td colspan="2">可用余额：<?= $member->account->user_money ?></td>
                        <td>可用积分：<?= $member->account->user_integral ?></td>
                        <td>成长值：<?= $member->account->user_growth ?></td>
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
                        <td colspan="6">手机号码：<?= $member['mobile'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            推荐人：
                            <?php if ($member->parent) { ?>
                                <a href="<?= Url::toRoute(['/member/member/view', 'id' => $member->pid]) ?>" class="openIframeView blue"><?= Html::encode($member->parent->nickname) ?></a>
                            <?php } else { ?>
                                无
                            <?php } ?>
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
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#custom-1">余额日志</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-2">积分日志</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-3">成长值日志</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#custom-4">消费日志</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-1">

                    </div>
                    <div class="tab-pane fade" id="custom-2">

                    </div>
                    <div class="tab-pane fade" id="custom-3">

                    </div>
                    <div class="tab-pane fade" id="custom-4">

                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

