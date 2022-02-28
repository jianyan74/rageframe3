<?php

use common\helpers\Url;
use common\helpers\Html;
use common\helpers\AddonHelper;

$this->title = '安装插件';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 已安装的插件</a></li>
                <li class="active"><a href="<?= Url::to(['local']) ?>"> 安装插件</a></li>
                <li><a href="<?= Url::to(['create']) ?>"> 设计新插件</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>图标</th>
                            <th>插件名称</th>
                            <th>作者</th>
                            <th>简单介绍</th>
                            <th>版本号</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $key => $vo) { ?>
                            <tr>
                                <td class="feed-element" style="width: 70px">
                                    <?php
                                    if ($path = AddonHelper::getAddonIcon($vo['name'])) {
                                        echo Html::img($path, [
                                            'class' => 'img-rounded m-t-xs img-responsive',
                                            'width' => '64',
                                            'height' => '64',
                                        ]);
                                    } else {
                                        echo '<span class="iconfont iconchajian blue" style="font-size: 50px"></span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <h4><?= Html::encode($vo['title']) ?></h4>
                                    <small><?= Html::encode($vo['name']) ?></small>
                                </td>
                                <td><?= Html::encode($vo['author']) ?></td>
                                <td><?= Html::encode($vo['brief_introduction']) ?></td>
                                <td><?= Html::encode($vo['version']) ?></td>
                                <td>
                                    <a href="<?= Url::to(['install', 'name' => $vo['name']]) ?>" onclick="install(this);return false;"><span class="btn btn-primary btn-sm">安装插件</span></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function install(that) {
        var href = $(that).attr('href');

        swal({
            title: '安装中...',
            text: '请不要关闭窗口，等待安装',
            button: "确定",
        });

        $.ajax({
            type: "get",
            url: href,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal("安装成功", "小手一抖就打开了一个框", "success").then((value) => {
                        location.reload();
                    });
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
