<footer class="rf-main-footer text-center">
    <div class="pb-2">
        当前版本: <?= Yii::$app->services->base->version(); ?>
    </div>
    <?= Yii::$app->services->config->backendConfig('web_copyright'); ?>
</footer>

