<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\TooManyRequestsHttpException;
use common\components\TrafficShaper;
use common\helpers\Auth;

/**
 * 令牌桶 - 限流行为
 *
 * Class TrafficShaperBehavior
 * @package common\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class TrafficShaperBehavior extends Behavior
{
    /**
     * @var bool whether to include rate limit headers in the response
     */
    public $enable = true;

    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @throws TooManyRequestsHttpException
     */
    public function beforeAction($event)
    {
        if ($this->enable == false) {
            return;
        }

        $config = Yii::$app->services->config->backendConfigAll();
        $isOpen = $config['current_limiting_is_open'] ?? false;
        if ($isOpen == false) {
            return;
        }

        $routes = $config['current_limiting_route'] ?? [];
        $whiteList = $config['current_limiting_white_list'] ?? [];
        !is_array($routes) && $routes = Json::decode($routes);
        !is_array($whiteList) && $whiteList = Json::decode($whiteList);
        $whiteListRoutes = $whiteList['route'] ?? [];

        $url = explode('?', Yii::$app->request->getUrl());
        foreach ($routes as $route) {
            if ($url[0] == $route['route']) {
                $trafficShaper = new TrafficShaper($route['num'], $route['route']);
                if (!$trafficShaper->get()) {
                    // 是否在白名单内
                    if (!empty($whiteListRoutes) && !in_array($url[0], $whiteListRoutes)) {
                        throw new TooManyRequestsHttpException('访问太火爆，请稍候');
                    }
                }
            }

            if ($route['route'] == '*') {
                $trafficShaper = new TrafficShaper($route['num'], $route['route']);
                if (!$trafficShaper->get()) {
                    // 是否在白名单内
                    if (!empty($whiteListRoutes) && !in_array($url[0], $whiteListRoutes)) {
                        throw new TooManyRequestsHttpException('访问太火爆，请稍候');
                    }
                }
            }
        }
    }
}
