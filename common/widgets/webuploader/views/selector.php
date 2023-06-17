<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use common\helpers\ArrayHelper;
use common\enums\AttachmentDriveEnum;
use common\enums\AttachmentUploadTypeEnum;

$param = Yii::$app->request->get();
unset($param['cate_id'], $param['page'], $param['per-page']);

$this->title = '素材选择';

?>

<div class="col-12 box-id" data-id="<?= $boxId?>" style="padding: 15px">
    <div class="row">
        <div class="col-2">
            <div class="box box-solid">
                <div class="box-header with-border pt-4 pl-3">
                    <h3 class="rf-box-title">素材组别</h3>
                </div>
                <div class="box-body" style="padding-top: 0">
                    <ul class="nav nav-pills nav-stacked" style="height: 430px; overflow-y: auto">
                        <li class="nav-item">
                            <a class="nav-link <?php if ($cateId == ''){ ?>blue<?php } ?>" href="<?= Url::to(ArrayHelper::merge(['selector'], $param)) ?>"> 默认分组 (<?= $cateCountMap[0] ?? 0; ?>)</a>
                            <?php foreach ($cates as $k => $cate) { ?>
                                <a
                                        class="nav-link <?php if ($cate['id'] == $cateId){ ?>blue<?php } ?>"
                                        href="<?= Url::to(ArrayHelper::merge(['selector', 'cate_id' => $cate['id']], $param)) ?>">
                                    <?= Html::encode($cate['title']); ?> (<?= $cateCountMap[$cate['id']] ?? 0; ?>)
                                </a>
                            <?php } ?>
                        </li>
                    </ul>
                    <div class="hr-line-dashed"></div>
                    <div class="text-center">
                        <?= Html::a('添加分组', ['cate-ajax-edit', 'type' => $uploadType], [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-10">
            <div class="box">
                <div class="box-body no-padding" style="padding-top: 0">
                    <div class="col-12 pt-3">
                        <?php $form = ActiveForm::begin([
                            'action' => '',
                            'method' => 'get',
                        ]); ?>
                        <div class="row">
                            <div class="col-lg-1">
                                <?= Html::dropDownList('year', $year, ArrayHelper::merge(['' => '不限年份'] , ArrayHelper::numBetween(2021, date('Y'), true, 1, '年')), [
                                    'class' => 'form-control',
                                ])?>
                            </div>
                            <div class="col-lg-1">
                                <?= Html::dropDownList('month', $month, ArrayHelper::merge(['' => '不限月份'] , ArrayHelper::numBetween(1, 12, true, 1, '月')), [
                                    'class' => 'form-control',
                                ])?>
                            </div>
                            <div class="col-lg-2">
                                <?= Html::dropDownList('drive', $drive, ArrayHelper::merge(['' => '不限存储类型'] , AttachmentDriveEnum::getMap()), [
                                    'class' => 'form-control',
                                ])?>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group m-b">
                                    <?= Html::input('text', 'keyword', $keyword, [
                                        'class' => 'form-control',
                                        'placeholder' => '关键字查询'
                                    ]); ?>
                                    <?= Html::tag('span', '<button class="btn btn-white" type="submit"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                                </div>
                            </div>
                            <div class="col-lg-5 text-right">
                                <?= \common\widgets\webuploader\Files::widget([
                                    'name' => 'upload',
                                    'value' => '',
                                    'type' => $uploadType,
                                    'theme' => 'selector-upload',
                                    'config' => [
                                        'pick' => [
                                            'multiple' => true,
                                        ],
                                        'formData' => [
                                            'drive' => $uploadDrive, // 默认本地 可修改 qiniu/oss/cos 上传
                                            'cate_id' => $cateId, // 默认本地 可修改 qiniu/oss/cos 上传
                                        ],
                                    ]
                                ])?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <ul class="mailbox-attachments clearfix" id="rfAttachmentList" data-multiple="<?= $multiple?>">
                        <?php foreach ($models as $model) {?>
                            <li>
                                <div class="border-color-gray" data-id="<?= $model['id']; ?>" data-name="<?= $model['name']; ?>" data-url="<?= $model['url']; ?>" data-upload_type="<?= $model['upload_type']; ?>">
                                    <?php if ($model['upload_type'] == AttachmentUploadTypeEnum::IMAGES) { ?>
                                        <span class="mailbox-attachment-icon has-img">
                                                <img src="<?= $model['url']; ?>" style="height: 130px">
                                        </span>
                                    <?php } else { ?>
                                        <span class="mailbox-attachment-icon">
                                                <i class="fa fa-file-alt"></i>
                                        </span>
                                    <?php } ?>
                                    <div class="mailbox-attachment-info">
                                        <a href="<?= $model['url']; ?>" target="_blank" class="mailbox-attachment-name">
                                            <span><i class="fa fa-paperclip"></i> <?= $model['name']; ?></span>
                                        </a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                            <span><?= $model['format_size']; ?></span>
                                            <a href="<?= Url::to(['destroy', 'id' => $model['id']])?>" onclick="rfTwiceAffirm(this, '确认删除文件么？', '请谨慎操作');return false;" class="btn btn-sm float-right gray"><i class="fas fa-trash"></i></a>
                                            <a href="<?= Url::to(['ajax-edit', 'id' => $model['id'], 'type' => $model['upload_type']])?>" data-toggle="modal" data-target="#ajaxModal" class="btn btn-sm float-right gray"><i class="fa fa-edit"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="row">
                        <div class="col-sm-12 pb-2">
                            <?= LinkPager::widget([
                                'pagination' => $pages,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
