<?php

namespace common\widgets\linkage;

use common\helpers\StringHelper;
use Yii;
use yii\base\Widget;

/**
 * Class Linkage
 * @package common\widgets\provinces
 * @author jianyan74 <751393839@qq.com>
 */
class Linkage extends Widget
{
    /**
     * 一级
     *
     * @var array
     */
    public $one = [
        'name' => 'province_id', // 字段名称
        'title' => '请选择省', //默认选择
        'list' => [], // 默认数据
    ];

    /**
     * 二级
     *
     * @var array
     */
    public $two = [
        'name' => 'city_id', // 字段名称
        'title' => '请选择市', //默认选择
        'list' => [], // 默认数据
    ];

    /**
     * 二级
     *
     * @var array
     */
    public $three = [
        'name' => 'area_id', // 字段名称
        'title' => '请选择区', //默认选择
        'list' => [], // 默认数据
    ];

    /**
     * 四级
     *
     * @var array
     */
    public $four = [
        'name' => 'township_id', // 字段名称
        'title' => '请选择乡/镇', //默认选择
        'list' => [], // 默认数据
    ];

    /**
     * 五级
     *
     * @var array
     */
    public $five = [
        'name' => 'village_id', // 字段名称
        'title' => '请选择村/社区', //默认选择
        'list' => [], // 默认数据
    ];

    /**
     * 显示类型
     *
     * long/short
     *
     * @var string
     */
    public $template = 'long';

    /**
     * 关联的ajax url
     *
     * @var
     */
    public $url;

    /**
     * 级别
     *
     * @var int
     */
    public $level = 3;

    /**
     * 模型
     *
     * @var array
     */
    public $model;

    /**
     * 表单
     * @var
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        empty($this->url) && $this->url = Yii::$app->urlManager->createUrl(['/provinces/child']);
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->level >= 1 && isset($this->one['list']) && empty($this->one['list'])) {
            $this->one['list'] = Yii::$app->services->provinces->getCityMapByPid();
        }

        if ($this->level >= 2 && isset($this->two['list']) && empty($this->two['list'])) {
            $oneName = $this->one['name'];
            $this->two['list'] = Yii::$app->services->provinces->getCityMapByPid($this->model->$oneName ?? 0, 2);
        }

        if ($this->level >= 3 && isset($this->three['list']) && empty($this->three['list'])) {
            $twoName = $this->two['name'];
            $this->three['list'] = Yii::$app->services->provinces->getCityMapByPid($this->model->$twoName ?? 0, 3);
        }

        if ($this->level >= 4 && isset($this->four['list']) && empty($this->four['list'])) {
            $threeName = $this->three['name'];
            $this->four['list'] = Yii::$app->services->provinces->getCityMapByPid($this->model->$threeName ?? 0, 4);
        }

        if ($this->level >= 5 && isset($this->five['list']) && empty($this->five['list'])) {
            $foueName = $this->four['name'];
            $this->five['list'] = Yii::$app->services->provinces->getCityMapByPid($this->model->$foueName ?? 0, 5);
        }

        return $this->render('index', [
            'form' => $this->form,
            'model' => $this->model,
            'random' => StringHelper::random(20),
            'one' => $this->one,
            'two' => $this->two,
            'three' => $this->three,
            'four' => $this->four,
            'five' => $this->five,
            'url' => $this->url,
            'level' => $this->level,
            'template' => $this->template,
        ]);
    }
}
