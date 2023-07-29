<?php
use common\helpers\Html;

$cityId = $areaId = $townshipId = $villageId = -1;
$col = 12;
if ($template == 'short') {
    $col = $level <= 3 ? 12 / $level : 4;
}

$level >= 2 && $cityId = Html::getInputId($model, $two['name']);
$level >= 3 && $areaId = Html::getInputId($model, $three['name']);
$level >= 4 &&$townshipId = Html::getInputId($model, $four['name']);
$level >= 5 &&$villageId = Html::getInputId($model, $five['name']);
?>

<div class="row">
    <div class="col-<?= $col; ?>">
        <?php if ($level >= 1){ ?>
            <?= $form->field($model, $one['name'])->dropDownList($one['list'], [
                'prompt' => $one['title'],
                'onchange' => "widgetProvinces" . $random . "(this, 1, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]); ?>
        <?php }?>
    </div>
    <div class="col-<?= $col; ?>">
        <?php if ($level >= 2){ ?>
            <?= $form->field($model, $two['name'])->dropDownList($two['list'], [
                'prompt' => $two['title'],
                'onchange' => "widgetProvinces" . $random . "(this, 2, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]); ?>
        <?php }?>
    </div>
    <div class="col-<?= $col; ?>">
        <?php if ($level >= 3){ ?>
            <?= $form->field($model, $three['name'])->dropDownList($three['list'], [
                'prompt' => $three['title'],
                'onchange' => "widgetProvinces" . $random . "(this, 3, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        <?php }?>
    </div>
    <div class="col-<?= $col; ?>">
        <?php if ($level >= 4){ ?>
            <?= $form->field($model, $four['name'])->dropDownList($four['list'], [
                'prompt' => $four['title'],
                'onchange' => "widgetProvinces" . $random . "(this, 4, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        <?php }?>
    </div>
    <div class="col-<?= $col; ?>">
        <?php if ($level >= 5){ ?>
            <?= $form->field($model, $five['name'])->dropDownList($five['list'], [
                'prompt' => $five['title'],
                'onchange' => "widgetProvinces" . $random . "(this, 5, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        <?php }?>
    </div>
</div>

<script>
    function widgetProvinces<?= $random ?>(obj, type_id, cityId, areaId, townshipId, villageId) {
        switch (type_id) {
            case 1 :
                $(".form-group.field-" + areaId).hide();
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 2 :
                $(".form-group.field-" + areaId).hide();
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 3 :
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 4 :
                $(".form-group.field-" + villageId).hide();
                break;
        }

        var pid = $(obj).val();
        $.ajax({
            type: "get",
            url: "<?= $url; ?>",
            dataType: "json",
            data: {type_id: type_id, pid: pid},
            success: function (data) {
                switch (type_id) {
                    case 1 :
                        $("select#" + cityId + "").html(data);
                        break;
                    case 2 :
                        $(".form-group.field-" + areaId).show();
                        $("select#" + areaId + "").html(data);
                        break;
                    case 3 :
                        $(".form-group.field-" + townshipId).show();
                        $("select#" + townshipId + "").html(data);
                        break;
                    case 4 :
                        $(".form-group.field-" + villageId).show();
                        $("select#" + villageId + "").html(data);
                        break;
                }
            }
        });
    }
</script>
