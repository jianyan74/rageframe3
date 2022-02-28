<?php

namespace console\controllers;

use Yii;
use yii\helpers\Json;
use yii\console\Controller;
use common\components\TrafficShaper;

/**
 * 令牌桶限流 - 添加器
 *
 * php ./yii traffic-shaper/run
 * php ./yii traffic-shaper/info
 *
 * Class TrafficShaperController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class TrafficShaperController extends Controller
{
    /**
     * 添加令牌数量
     */
    public function actionRun()
    {
        $time = Yii::$app->services->config->backendConfig('current_limiting_time');
        $routes = Yii::$app->services->config->backendConfig('current_limiting_route');
        !is_array($routes) && $routes = Json::decode($routes);
        $unit = (int) (60 / $time);

        echo '------------------------------- ' . date('Y-m-d H:i:s') . ' -------------------------------' . PHP_EOL;

        for ($i = 0; $i < $unit; $i++) {
            foreach ($routes as $route) {
                (new TrafficShaper($route['num'], $route['route']))->add($route['num']);

                echo '「' . $route['route'] . '」 总次数为：'  . $route['num'] . PHP_EOL;
            }

            sleep($time);
        }

        echo '------------------------------- end -------------------------------' . PHP_EOL;
    }

    /**
     * 查询当前配置
     */
    public function actionInfo()
    {
        $routes = Yii::$app->services->config->backendConfig('current_limiting_route');
        !is_array($routes) && $routes = Json::decode($routes);

        echo '------------------------------- ' . date('Y-m-d H:i:s') . ' -------------------------------' . PHP_EOL;
        foreach ($routes as $route) {
            $model = new TrafficShaper(0, $route['route']);

            echo '「' . $route['route'] . '」 剩余可用次数为：'  . $model->info() . PHP_EOL;
        }

        echo '------------------------------- end -------------------------------' . PHP_EOL;
    }
}
