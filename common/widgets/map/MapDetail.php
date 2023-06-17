<?php

namespace common\widgets\map;

use Yii;
use yii\base\Widget;

/**
 * 地图经纬度选择器
 *
 * Class Map
 * @package common\widgets\map
 * @author jianyan74 <751393839@qq.com>
 */
class MapDetail extends Widget
{
    public $title = '骑行路径';

    /**
     * 秘钥
     *
     * @var string
     */
    public $secret_key = '';

    /**
     * 类型
     *
     * 默认高德
     *
     * amap 高德
     * tencent 腾讯
     * baidu 高德
     *
     * @var string
     */
    public $type = 'amap';

    public $start = [
        'label' => '',
        'lng' => '',
        'lat' => '',
    ];

    public $end = [
        'label' => '',
        'lng' => '',
        'lat' => '',
    ];

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        return $this->render('detail/index', [
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'label' => $this->label,
            'type' => $this->type,
            'secret_key' => $this->secret_key,
        ]);
    }
}
