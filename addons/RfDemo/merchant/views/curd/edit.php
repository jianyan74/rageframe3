<?php

use common\widgets\cropper\Cropper;
use common\widgets\linkage\Linkage;
use common\widgets\map\Map;
use common\widgets\map\MapOverlay;
use common\widgets\ueditor\UEditor;
use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\enums\GenderEnum;
use common\helpers\DateHelper;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '增删改查', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="col-12">
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'description')->textarea(); ?>
                    <?= $form->field($model, 'gender')->radioList(GenderEnum::getMap()); ?>
                    <?= $form->field($model, 'cate_id')->widget(common\widgets\cascader\Cascader::class, [
                        'data' => $cates,
                    ])->hint('适合数据量不大的，否则会卡顿，大数据量建议使用下面的多级联动方式'); ?>
                    <?= $form->field($model, 'cate_ids')->widget(common\widgets\cascader\Cascader::class, [
                        'data' => $cates,
                        'multiple' => true
                    ]); ?>
                    <?= Linkage::widget([
                        'form' => $form,
                        'model' => $model,
                        'template' => 'short',
                    ]); ?>
                    <?= $form->field($model, 'tag')->widget(kartik\select2\Select2::class, [
                        'data' => [1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); ?>
                    <?= $form->field($model, 'color')->widget(kartik\color\ColorInput::class, [
                        'options' => ['placeholder' => 'Select color ...'],
                    ]); ?>
                    <?= $form->field($model, 'multiple_input')->widget(unclead\multipleinput\MultipleInput::class, [
                        'max' => 6,
                        'iconSource' => 'fa',
                        'columns' => [
                            [
                                'name' => 'user_id',
                                'type' => 'dropDownList',
                                'title' => '用户',
                                'defaultValue' => 1,
                                'items' => [
                                    1 => '用户A',
                                    2 => '用户B',
                                    3 => '用户C',
                                ],
                            ],
                            [
                                'name' => 'day',
                                'type' => DatePicker::class,
                                'title' => '日期',
                                'value' => function ($data) {
                                    return $data['day'] ?? '';
                                },
                                'items' => [
                                    '0' => 'Saturday',
                                    '1' => 'Monday',
                                ],
                                'options' => [
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'todayHighlight' => true,
                                        'todayBtn' => true,//今日按钮显示
                                    ],
                                ],
                            ],
                            [
                                'name' => 'priority',
                                'title' => '排序',
                                // 'enableError' => false,
                                'options' => [
                                    'class' => 'input-priority',
                                ],
                            ],
                        ],
                    ]);
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d') : $model->date,
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ],
                            ]); ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'time', [
                                'template' => "{label}{input}\n{hint}\n{error}",
                            ])->widget(kartik\time\TimePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => DateHelper::secondToTime($model->time),
                                ],
                                'pluginOptions' => [
                                    'showSeconds' => true,
                                    'showMeridian' => false,
                                    'minuteStep' => 1,
                                    'secondStep' => 5,
                                ],
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'start_time')->widget(DateTimePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',
                                        $model->start_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ],
                            ]); ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'end_time')->widget(DateTimePicker::class, [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',
                                        $model->start_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ],
                            ]); ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'longitude_and_latitude')->widget(Map::class, [
                        'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
                    ])->hint('点击地图某处才会获取到经纬度，否则默认北京'); ?>
                    <?= $form->field($model, 'map_overlay')->widget(MapOverlay::class, [
                        'longitude' => '116.456270',
                        'latitude' => '39.919990',
                    ]); ?>
                    <?= $form->field($model, 'head_portrait')->widget(Cropper::class, [
                        // 'theme' => 'default',
                        'config' => [
                            // 可设置自己的上传地址, 不设置则默认地址
                            // 'server' => '',
                        ],
                    ])->hint('裁剪上传'); ?>
                    <?= $form->field($model, 'cover')->widget(Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            // 可设置自己的上传地址, 不设置则默认地址
                            // 'server' => '',
                            'pick' => [
                                'multiple' => false,
                            ],
                            // 不配置则不生成缩略图
                            'formData' => [
                                // 不配置则不生成缩略图
                                'thumb' => [
                                    [
                                        'width' => 100,
                                        'height' => 100,
                                    ],
                                    [
                                        'width' => 200,
                                        'height' => 200,
                                    ],
                                ],
                                'drive' => 'local',// 默认本地 支持qiniu/oss/cos 上传
                            ],
                        ],
                    ])->hint('开启缩略图'); ?>
                    <?= $form->field($model, 'covers')->widget(Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            // 可设置自己的上传地址, 不设置则默认地址
                            // 'server' => '',
                            'pick' => [
                                'multiple' => true,
                            ],
                            'formData' => [
                                // 保留原名称
                                'originalName' => true,
                            ],
                        ],
                    ])->hint('保留图片原名称'); ?>
                    <?= $form->field($model, 'file')->widget(Files::class, [
                        'type' => 'files',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                            'formData' => [
                                // 'drive' => 'cos', // 默认本地 可修改 qiniu/oss/cos 上传
                            ], // 表单参数
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 1024 * 1024 * 5,// 分片大小
                        ],
                    ]); ?>
                    <?= $form->field($model, 'files')->widget(Files::class, [
                        'type' => 'files',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],
                            // 表单参数
                            'formData' => [
                                'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
                            ],
                            'chunked' => true,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ],
                    ])->hint('开启分片上传，切片大小: 512k。注意：只支持本地上传'); ?>
                    <?= $form->field($model, 'content')->widget(UEditor::class, [
                        'formData' => [
                            'drive' => 'local', // 默认本地 支持qiniu/oss/cos 上传
                            'poster' => false, // 上传视频时返回视频封面图，开启此选项需要安装 ffmpeg 命令
                            'thumb' => [
                                [
                                    'width' => 100,
                                    'height' => 100,
                                ],
                            ],
                        ],
                    ]) ?>
                    <?= $form->field($model, 'sort')->textInput(); ?>
                    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-12 col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
