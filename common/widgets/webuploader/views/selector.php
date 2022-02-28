<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use common\helpers\ArrayHelper;
use common\enums\AttachmentDriveEnum;

$param = Yii::$app->request->get();
unset($param['cate_id']);

$this->title = '资源选择';

?>

<div class="row box-id" data-id="<?= $boxId?>">
    <div class="col-sm-12">
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
        <div class="row">
            <div class="col-7 col-sm-10" style="padding-right: 0">
                <div class="tab-content" id="vert-tabs-right-tabContent">
                    <div class="col-12" style="min-height: 400px">
                        <ul class="mailbox-attachments clearfix pt-2" id="rfAttachmentList" data-multiple="<?= $multiple?>">
                            <?php foreach ($models as $model) {?>
                                <li>
                                    <div class="border-color-gray" data-id="<?= $model['id']; ?>" data-name="<?= $model['name']; ?>" data-url="<?= $model['url']; ?>" data-upload_type="<?= $model['upload_type']; ?>">
                                        <?php if ($model['upload_type'] == \common\enums\AttachmentUploadTypeEnum::IMAGES) { ?>
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
            <div class="col-5 col-sm-2" style="padding-left: 0">
                <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link <?php if ($cateId == ''){ ?>active<?php } ?>" href="<?= Url::to(ArrayHelper::merge(['selector'], $param)) ?>"> 全部文件</a>
                    <?php foreach ($cates as $k => $cate) { ?>
                        <a
                                class="nav-link <?php if ($cate['id'] == $cateId){ ?>active<?php } ?>"
                                href="<?= Url::to(ArrayHelper::merge(['selector', 'cate_id' => $cate['id']], $param)) ?>">
                            <?= Html::encode($cate['title']); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
