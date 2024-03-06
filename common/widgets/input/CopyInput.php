<?php

namespace common\widgets\input;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class CopyInput
 * @package common\widgets\input
 * @author jianyan74 <751393839@qq.com>
 */
class CopyInput extends InputWidget
{
    /**
     * @var string
     */
    public $title = '复制';

    /**
     * @var array
     */
    public $options = [];

    /**
     * @return string|void
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        if (empty($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : md5($name);
        }

        return $this->render('copy', [
            'value' => $value,
            'name' => $name,
            'options' => $this->options,
            'title' => $this->title,
        ]);
    }
}
