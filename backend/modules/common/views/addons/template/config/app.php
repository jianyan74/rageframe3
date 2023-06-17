<?php

use common\helpers\StringHelper;
use common\enums\AppEnum;

$menuCount = 0;
$menus = $menu[$appID] ?? [];
if (isset($menus['title'])) {
    $menuCount = count($menus['title']);
}

echo "<?php\n";
?>

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-puzzle-piece',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
<?php if (in_array($appID, AppEnum::api())) { ?>
            'v1' => [
                'class' => 'addons\<?= $model->name; ?>\<?= $appID ?>\modules\v1\Module',
            ],
            'v2' => [
                'class' => 'addons\<?= $model->name; ?>\<?= $appID ?>\modules\v2\Module',
            ],
<?php } ?>
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
<?php for ($i = 0; $i < $menuCount; $i++){
    if (!empty($menus['title'][$i]) && !empty($menus['name'][$i])){
        $params = !empty($menus['params'][$i]) ? StringHelper::parseAttr($menus['params'][$i]) : [];
        ?>
        [
            'title' => '<?= trim($menus['title'][$i]); ?>',
            'name' => '<?= trim($menus['name'][$i]); ?>',
            'icon' => '<?= trim($menus['icon'][$i]); ?>',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
            'params' => [
            <?php foreach ($params as $key => $param) { ?>
    '<?= trim($key); ?>' => '<?= trim($param); ?>',
            <?php } ?>

            ],
            'child' => [

            ],
        ],
    <?php }
}
?>

    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
<?php if (in_array($appID, AppEnum::admin())) { ?>
        [
            'title' => '所有权限',
            'name' => '*',
        ],
<?php } ?>
    ],
];
