<?php

namespace common\widgets\cascader;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;

/**
 *
 *
 * Class Cascader
 * @package common\widgets\area
 */
class Cascader extends InputWidget
{
    /**
     * 默认数据
     *
     * @var array
     *
     * [
     *     [
     *          'id' = '1',
     *          'title' = 'demo',
     *          'pid' = '0',
     *     ]
     * ]
     */
    public $data = [];

    /**
     * 默认重组 pid 值
     *
     * @var int
     */
    public $pid = 0;

    /**
     * 多选
     *
     * @var bool
     */
    public $multiple = false;

    /**
     * 开启选择任意级
     *
     * @var bool
     */
    public $changeOnSelect = false;

    /**
     * 动态加载
     *
     * @var bool
     */
    public $dynamicLoading = false;

    /**
     * @var array
     */
    public $options = [];

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        foreach ($this->data as $datum) {
            $this->items[] = [
                'value' => trim($datum['id']),
                'pid' => trim($datum['pid']),
                'label' => trim($datum['title']),
            ];
        }

        $this->items = ArrayHelper::itemsMerge($this->items, $this->pid, 'value', 'pid', 'children');
        $this->options = ArrayHelper::merge([
            'style' => 'width:420px',
            'placeholder' => '搜索或点击下拉选择'
        ], $this->options);

        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
        $this->boxId = md5($name) . StringHelper::uuid('uniqid');
    }

    /**
     * @return string
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        $selected = [];
        if ($this->multiple == true) {
            $name = $name . '[]';
            empty($value) && $value = [];
            foreach ($value as $item) {
                $parents = ArrayHelper::getParents($this->data, $item);
                !empty($parents) && $selected[] = [
                    'parents' => $parents,
                    'id' => $item
                ];
            }
        } else {
            is_array($value) && $value = $value[0];
            $parents = ArrayHelper::getParents($this->data, $value);
            !empty($parents) && $selected[] = [
                'parents' => $parents,
                'id' => $value
            ];
        }

        empty($selected) && $selected[] = [
            'parents' => [],
            'id' => 0
        ];

        $this->registerClientScript();

        return $this->render('cascader', [
            'value' => $value,
            'name' => $name,
            'selected' => $selected,
            'items' => Json::encode($this->items),
            'boxId' => $this->boxId,
            'multiple' => $this->multiple,
            'options' => $this->options,
            'changeOnSelect' => $this->changeOnSelect,
            'dynamicLoading' => $this->dynamicLoading,
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        $view->registerJs(<<<Js
    $(document).on("click", ".cascader-input-plus", function (e) {
        var boxId = $(this).parent().parent().parent().data('id');
        var random = getRandomString(20);
        
        var html = template('cascaderBoxHtml-' + boxId, {boxId: random});
        $(this).parent().parent().parent().parent().append(html);
        cascaderboxInit(random);
    });
Js
        );
    }
}
