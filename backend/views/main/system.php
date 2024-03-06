<?php

use common\helpers\Html;
use common\helpers\RegularHelper;

$this->title = '系统信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$prefix = !RegularHelper::verify('url', Yii::getAlias('@attachurl')) ? Yii::$app->request->hostInfo : '';

?>

<div class="row">
    <div class="col-xs-7 col-sm-7">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 环境配置</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP 版本</td>
                        <td><?= phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td>Mysql 版本</td>
                        <td><?= Yii::$app->db->pdo->getAttribute(PDO::ATTR_SERVER_VERSION); ?></td>
                    </tr>
                    <tr>
                        <td>解析引擎</td>
                        <td><?= Html::encode($_SERVER['SERVER_SOFTWARE']); ?></td>
                    </tr>
                    <tr>
                        <td>数据库大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($mysqlSize, 2); ?></td>
                    </tr>
                    <tr>
                        <td>附件目录</td>
                        <td><?= $prefix.Yii::getAlias('@attachurl'); ?>/</td>
                    </tr>
                    <tr>
                        <td>附件目录大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($attachmentSize, 2); ?></td>
                    </tr>
                    <tr>
                        <td>超时时间</td>
                        <td><?= ini_get('max_execution_time'); ?>秒</td>
                    </tr>
                    <tr>
                        <td>客户端信息</td>
                        <td><?= Html::encode($_SERVER['HTTP_USER_AGENT']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-5 col-sm-5">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> 系统信息</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>系统全称</td>
                        <td><?= Yii::$app->params['exploitFullName']; ?> <span class="label label-default"><?= Yii::$app->params['devPattern'] ?> 模式</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>重量级全栖框架，为二次开发而生。</td>
                    </tr>
                    <tr>
                        <td>系统版本</td>
                        <td>
                            <?= Yii::$app->services->base->version(); ?>
                            <span class="label label-default"><?= $sysVersion; ?></span>
                            <small class="blue" onclick="onLineUpgrade(this);return false;">在线升级</small>
                        </td>
                    </tr>
                    <tr>
                        <td>Yii2 版本</td>
                        <td>
                            <?= Yii::getVersion(); ?><?php if (YII_DEBUG) {
                                echo ' <span class="label label-default">开发模式</span>';
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>官网</td>
                        <td><?= Yii::$app->params['exploitOfficialWebsite'] ?></td>
                    </tr>
                    <tr>
                        <td>官方 QQ 群</td>
                        <td>
                            <a href="https://jq.qq.com/?_wv=1027&k=4BeVA2r" target="_blank">655084090</a>,
                            <a href="https://jq.qq.com/?_wv=1027&k=Wk663e9N" target="_blank">1148015133</a>
                        </td>
                    </tr>
                    <tr>
                        <td>GitHub</td>
                        <td><?= Yii::$app->params['exploitGitHub'] ?></td>
                    </tr>
                    <tr>
                        <td>开发者</td>
                        <td><?= Yii::$app->params['exploitDeveloper'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-lemon"></i> PHP信息</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP 执行方式</td>
                        <td><?= php_sapi_name(); ?></td>
                    </tr>
                    <tr>
                        <td>扩展支持</td>
                        <td>
                            <?= extension_loaded('gd')
                                ? '<span class="label label-primary">gd</span>'
                                : '<span class="label label-default">gd</span>'; ?>
                            <?= extension_loaded('imagick')
                                ? '<span class="label label-primary">imagick</span>'
                                : '<span class="label label-default">imagick</span>'; ?>
                            <?= extension_loaded('curl')
                                ? '<span class="label label-primary">curl</span>'
                                : '<span class="label label-default">curl</span>'; ?>
                            <?= extension_loaded('fileinfo')
                                ? '<span class="label label-primary">fileinfo</span>'
                                : '<span class="label label-default">fileinfo</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">intl</span>'
                                : '<span class="label label-default">intl</span>'; ?>
                            <?= extension_loaded('mbstring')
                                ? '<span class="label label-primary">mbstring</span>'
                                : '<span class="label label-default">mbstring</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">exif</span>'
                                : '<span class="label label-default">exif</span>'; ?>
                            <?= extension_loaded('openssl')
                                ? '<span class="label label-primary">openssl</span>'
                                : '<span class="label label-default">openssl</span>'; ?>
                            <?= extension_loaded('Zend OPcache')
                                ? '<span class="label label-primary">opcache</span>'
                                : '<span class="label label-default">opcache</span>'; ?>
                            <?= extension_loaded('redis')
                                ? '<span class="label label-primary">redis</span>'
                                : '<span class="label label-default">redis</span>'; ?>
                            <?= extension_loaded('swoole')
                                ? '<span class="label label-primary">swoole</span>'
                                : '<span class="label label-default">swoole</span>'; ?>
                            <?= extension_loaded('mongodb')
                                ? '<span class="label label-primary">mongodb</span>'
                                : '<span class="label label-default">mongodb</span>'; ?>
                            <?= extension_loaded('amqp')
                                ? '<span class="label label-primary">amqp</span>'
                                : '<span class="label label-default">amqp</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>禁用的函数</td>
                        <td>
                            <?php if (is_array($disableFunctions)) { ?>
                                <?php foreach ($disableFunctions as $function) { ?>
                                    <span class="label label-default"><?= $function; ?></span>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="label label-default"><?= $disableFunctions; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>脚本内存限制</td>
                        <td><?= ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <td>文件上传限制</td>
                        <td><?= ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <td>Post 数据最大尺寸</td>
                        <td><?= ini_get('post_max_size'); ?></td>
                    </tr>
                    <tr>
                        <td>Socket 超时时间</td>
                        <td><?= ini_get('default_socket_timeout'); ?> 秒</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function onLineUpgrade(that) {
        var href = "<?= \yii\helpers\Url::to(['/common/addons/on-line-upgrade'])?>";
        var title = '确认在线升级吗?';
        var dialogText = '请注意先备份好服务器文件信息及数据库信息';

        swal(title, {
            buttons: {
                cancel: "取消",
                defeat: '确定'
            },
            title: title,
            text: dialogText,
            // icon: "warning",
        }).then(function (value) {
            switch (value) {
                case "defeat":
                    onLineUpgradeExecute(href);
                    break;
                default:
            }
        });
    }

    function onLineUpgradeExecute(href) {
        swal({
            title: '在线升级中...',
            text: '请不要关闭窗口',
            button: "确定",
        });

        $.ajax({
            type: "get",
            url: href,
            dataType: "json",
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    swal("升级成功", "小手一抖就打开了一个框", "success").then((value) => {
                        location.reload();
                    });
                } else {
                    setTimeout(function () {
                        swal({
                            title: '升级提示',
                            text: data.message,
                            button: "确定",
                        });
                    }, 1000)
                }
            }
        });
    }
</script>
