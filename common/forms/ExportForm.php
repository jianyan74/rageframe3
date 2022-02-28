<?php

namespace common\forms;

use yii\base\Model;

/**
 * Class ExportForm
 * @package common\forms
 */
abstract class ExportForm extends Model
{
    /**
     * 默认选中字段
     *
     * @var array
     */
    public $info = [];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['info', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'info' => '',
        ];
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        $data = [];
        $header = $this->defaultHeader();
        foreach ($header as $item) {
            $data[$item[1]] = $item[0];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function header()
    {
        $header = $this->defaultHeader();
        foreach ($header as $key => $item) {
            if (!in_array($item[1], $this->info)) {
                unset($header[$key]);
            }
        }

        return $header;
    }

    /**
     * @return array
     */
    abstract public function defaultHeader(): array;
}
