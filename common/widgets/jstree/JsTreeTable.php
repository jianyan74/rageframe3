<?php

namespace common\widgets\jstree;

use yii\helpers\Json;
use yii\widgets\InputWidget;
use common\enums\StatusEnum;
use common\widgets\jstree\assets\AppAsset;

/**
 * Class JsTreeTable
 * @package common\widgets\jstree
 * @author jianyan74 <751393839@qq.com>
 */
class JsTreeTable extends InputWidget
{
    public $title = '节点管理';

    /**
     * ID
     *
     * @var
     */
    public $name;

    /**
     * @var string
     */
    public $theme = 'table';

    /**
     * ajax 加载数据
     *
     * @var bool
     */
    public $ajax = false;

    /**
     * 默认数据
     *
     * @var array
     */
    public $defaultData = [];

    /**
     * 列表
     *
     * @var
     */
    public $listUrl;

    /**
     * 编辑
     *
     * @var string
     */
    public $editUrl;

    /**
     * 删除
     *
     * @var string
     */
    public $deleteUrl;

    /**
     * 移动
     *
     * @var string
     */
    public $moveUrl;

    /**
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();

        $defaultData = $this->defaultData;
        $jsTreeData = [];
        if (!empty($defaultData)) {
            foreach ($defaultData as $datum) {
                $jsTreeData[] = [
                    'id' => $datum['id'],
                    'parent' => !empty($datum['pid']) ? $datum['pid'] : '#',
                    'text' => trim($datum['title']),
                    'icon' => 'fa fa-folder'
                ];
            }
        }

        return $this->render($this->theme, [
            'title' => $this->title,
            'name' => $this->name,
            'editUrl' => $this->editUrl,
            'deleteUrl' => $this->deleteUrl,
            'moveUrl' => $this->moveUrl,
            'listUrl' => $this->listUrl,
            'ajax' => $this->ajax == false ? StatusEnum::DISABLED : StatusEnum::ENABLED,
            'jsTreeData' => Json::encode($jsTreeData),
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        AppAsset::register($view);
    }
}