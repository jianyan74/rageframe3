<?php

namespace addons\Wechat\services;

use Yii;
use common\components\Service;
use addons\Wechat\common\models\Menu;
use addons\Wechat\common\enums\MenuTypeEnum;

/**
 * Class MenuService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class MenuService extends Service
{
    /**
     * @param Menu $model
     * @param $data
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createSave(Menu $model, $data)
    {
        $buttons = [];
        foreach ($data as &$button) {
            $arr = [];
            // 判断是否有子菜单
            if (isset($button['sub_button'])) {
                $arr['name'] = $button['name'];

                foreach ($button['sub_button'] as &$sub) {
                    $sub_button = $this->mergeButton($sub);
                    $sub_button['name'] = $sub['name'];
                    $sub_button['type'] = $sub['type'];
                    $arr['sub_button'][] = $sub_button;
                }
            } else {
                $arr = $this->mergeButton($button);
                $arr['name'] = $button['name'];
                $arr['type'] = $button['type'];
            }

            $buttons[] = $arr;
        }

        $model->menu_data = $buttons;
        // 判断写入是否成功
        !$model->validate() && $this->error($model);

        // 个性化菜单
        if ($model->type == MenuTypeEnum::INDIVIDUATION) {
            $matchRule = [
                "tag_id" => $model->tag_id,
                "client_platform_type" => $model->client_platform_type,
            ];

            // 创建自定义菜单
            $menuResult = Yii::$app->wechat->app->menu->create($buttons, $matchRule);
            Yii::$app->services->base->getWechatError($menuResult);
            $model->menu_id = $menuResult['menuid'];
        } else {
            // 验证微信报错
            Yii::$app->services->base->getWechatError(Yii::$app->wechat->app->menu->create($buttons));
        }

        !$model->save() && $this->error($model);

        Menu::updateAll(['menu_data' => $buttons], ['id' => $model->id]);
    }

    /**
     * 合并前端过来的数据
     *
     * @param array $button
     * @return array
     */
    protected function mergeButton(array $button)
    {
        $arr = [];
        $menuTypes = MenuTypeEnum::type();
        if ($button['type'] == 'click' || $button['type'] == 'view') {
            $arr[$menuTypes[$button['type']]['meta']] = $button['content'];
        } elseif ($button['type'] == 'miniprogram') {
            $arr['appid'] = $button['appid'];
            $arr['pagepath'] = $button['pagepath'];
            $arr['url'] = $button['url'];
        } else {
            $arr[$menuTypes[$button['type']]['meta']] = $menuTypes[$button['type']]['value'];
        }

        return $arr;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function sync()
    {
        // 获取菜单列表
        $list = Yii::$app->wechat->app->menu->list();
        // 解析微信接口是否报错
        Yii::$app->services->base->getWechatError($list);

        // 开始获取自定义菜单同步
        if (!empty($list['menu'])) {
            $model = new Menu;
            $model->title = "默认菜单";
            $model = $model->loadDefaultValues();
            $model->menu_data = $list['menu']['button'];
            $model->menu_id = isset($list['menu']['menuid']) ? $list['menu']['menuid'] : '';
            $model->save();
        }

        // 个性化菜单
        if (!empty($list['conditionalmenu'])) {
            foreach ($list['conditionalmenu'] as $menu) {
                if (!($model = Menu::findOne(['menu_id' => $menu['menuid']]))) {
                    $model = new Menu;
                    $model = $model->loadDefaultValues();
                }

                $model->title = "个性化菜单";
                $model->attributes = $menu['matchrule'];
                $model->type = MenuTypeEnum::INDIVIDUATION;
                $model->tag_id = isset($menu['group_id']) ? $menu['group_id'] : '';
                $model->menu_data = $menu['button'];
                $model->menu_id = $menu['menuid'];
                $model->save();
            }
        }
    }
}
