<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if (!empty($lng2) && !empty($lat2)) { ?>
    <a href="<?= Url::to(['/map/riding-route',
        'type' => $type,
        'label' => $label,
        'lng' => $lng,
        'lat' => $lat,
        'label2' => $label2,
        'lng2' => $lng2,
        'lat2' => $lat2,
    ])?>" data-toggle="modal" data-target="#ajaxModalMax" class="blue"><?= $title ?></a>
<?php } else { ?>
    <a href="<?= Url::to(['/map/map-view',
        'type' => $type,
        'label' => $label,
        'lng' => $lng,
        'lat' => $lat,
    ])?>" data-toggle="modal" data-target="#ajaxModalMax"><i class="fa fa-map-marker blue"></i></a>
<?php } ?>
