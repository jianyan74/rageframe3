<?php

use common\helpers\DebrisHelper;
use common\helpers\Html;

?>

<div class="modal-header">
    <h4 class="modal-title">基本信息</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr>
            <td>设备/浏览器</td>
            <td><?= Html::encode($model['device']) ?></td>
        </tr>
        <tr>
            <td>行为</td>
            <td><?= Html::encode($model['behavior']) ?></td>
        </tr>
        <tr>
            <td>备注</td>
            <td><?= Html::encode($model['remark']) ?></td>
        </tr>
        <tr>
            <td>提交方法</td>
            <td><?= Html::encode($model['method']) ?></td>
        </tr>
        <tr>
            <td>控制器方法</td>
            <td><?= Html::encode($model['controller']); ?>/<?= Html::encode($model['action']); ?></td>
        </tr>
        <tr>
            <td>Url</td>
            <td><?= Html::encode($model['url']) ?></td>
        </tr>
        <tr>
            <td>IP</td>
            <td><?= $model->ip; ?></td>
        </tr>
        <tr>
            <td>地区</td>
            <td>
                <?php
                $data = [];
                !empty($model['country']) && $data[] = $model['country'];
                !empty($model['provinces']) && $data[] = $model['provinces'];
                !empty($model['city']) && $data[] = $model['city'];
                echo implode(' · ', $data);
                ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Get 数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->services->base->p(DebrisHelper::htmlEncode($model['get_data'])) ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Post 数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->services->base->p(DebrisHelper::htmlEncode($model['post_data'])) ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">Header 数据</td>
            <td style="max-width: 700px">
                <?php Yii::$app->services->base->p(DebrisHelper::htmlEncode($model['header_data'])) ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>