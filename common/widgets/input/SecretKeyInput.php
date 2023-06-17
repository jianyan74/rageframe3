<?php

namespace common\widgets\input;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class SecretKeyInput
 * @package common\widgets\input
 * @author jianyan74 <751393839@qq.com>
 */
class SecretKeyInput extends InputWidget
{
    public $options = [];
    public $number = 32;

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

        return $this->render('secret-key', [
            'value' => $value,
            'name' => $name,
            'options' => $this->options,
            'number' => $this->number,
        ]);
    }
}
