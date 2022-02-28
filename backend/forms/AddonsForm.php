<?php

namespace backend\forms;

use common\helpers\ArrayHelper;
use common\models\common\Addons;

/**
 * Class AddonsForm
 * @package backend\forms
 */
class AddonsForm extends Addons
{
    /**
     * @var
     */
    public $install = 'Install';

    /**
     * @var
     */
    public $uninstall = 'UnInstall';

    /**
     * @var
     */
    public $upgrade = 'Upgrade';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge([
            [['install', 'uninstall', 'upgrade', 'author'], 'required'],
        ], parent::rules());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'install' => '安装文件',
            'uninstall' => '卸载文件',
            'upgrade' => '更新文件',
        ], parent::attributeLabels());
    }
}
