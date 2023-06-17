<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$count = count($value);

?>

<div class="row" id="<?= $boxId; ?>">
    <div class="col-lg-12">
        <div class="input-group">
            <?= Html::textInput('address-scope', $count > 0 ? '已设置 ' . $count .  ' 个范围' : '', ['class' => 'form-control', 'disabled' => true, 'placeholder' => '请设置地图范围',]); ?>
            <span class="input-group-btn"><a href="javascript:void (0);" class="btn btn-white map-overlay">地图范围</a></span>
        </div>
        <div class="hidden">
            <a href="<?= Url::to(['/map-overlay/index', 'longitude' => $longitude, 'latitude' => $latitude, 'type' => $type, 'boxId' => $boxId,]) ?>" class="rfMap" data-toggle="modal" data-target="#ajaxModalMax"></a>
            <?= Html::hiddenInput($name, Json::encode($value), ['class' => 'overlay']); ?>
        </div>
    </div>
</div>

<script>
    var boxId = "<?= $boxId; ?>";
    $(document).on('map-' + boxId, function (e, boxId, data) {
        var str = '已设置 ' + data.length + ' 个范围';
        $('#' + boxId).find('.input-group input').val(str);
        $('#' + boxId).find('.overlay').val(JSON.stringify(data));
    });
</script>
