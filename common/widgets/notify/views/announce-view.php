<?php

use common\helpers\Html;

$this->title = '公告详情';
$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

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
                <h3 class="box-title" style="font-size: 20px"><?= $model['title']; ?></h3>
                <div class="box-tools">
                    <?= Yii::$app->formatter->asDatetime($model['created_at']); ?>
                </div>
            </div>
            <div class="box-body">
                <div class="col-lg-12">
                    <p><?= Html::decode($model['content']); ?></p>
                </div>
            </div>
            <div class="box-footer text-center">
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>