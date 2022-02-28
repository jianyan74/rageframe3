<?php

use yii\helpers\Html;

?>

<div class="modal-header">
    <h4 class="modal-title">基本信息</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body">
    <div class="form-group field-cate-title required">
        <div class="row">
            <div class="col-sm-2 text-right">
                <label class="control-label">经度(lng)</label>
            </div>
            <div class="col-sm-10">
                <?= Html::textInput('lng', $lng, ['class' => 'form-control', 'id' => 'rfMapLng']); ?>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="form-group field-cate-title required">
        <div class="row">
            <div class="col-sm-2 text-right">
                <label class="control-label">纬度(lat)</label>
            </div>
            <div class="col-sm-10">
                <?= Html::textInput('lat', $lat, ['class' => 'form-control', 'id' => 'rfMapLat']); ?>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal" id="rfMapClose">关闭</button>
    <button class="btn btn-primary" id="mapConfirm">确定</button>
</div>

<script>
    var boxId = "<?= $boxId;?>";
    // 选择
    $('#mapConfirm').click(function () {
        var lat = $('#rfMapLat').val();
        var lng = $('#rfMapLng').val();

        if (!lng) {
            rfWarning('请填写经度(lng)');
            return;
        }

        if (!lat) {
            rfWarning('请填写纬度(lat)');
            return;
        }

        var data = {lat: lat, lng: lng};
        $(document).trigger('map-' + boxId, [boxId, data]);
        // 关闭 model
        $('#rfMapClose').trigger('click');
        console.log(data);
    });

</script>