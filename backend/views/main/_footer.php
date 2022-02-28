<footer class="main-footer" style="position: fixed;left: 0;bottom: 0;width: 100%;">
    <div class="float-right d-none d-sm-block" style="padding-right: 200px">
        <strong><?= Yii::$app->services->config->backendConfig('web_copyright'); ?></strong>
    </div>
    <b>当前版本</b> <?= Yii::$app->services->base->version(); ?>
</footer>
