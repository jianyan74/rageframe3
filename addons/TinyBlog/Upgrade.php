<?php

namespace addons\TinyBlog;

use Yii;
use common\components\Migration;
use common\interfaces\AddonWidget;
use common\helpers\StringHelper;
use addons\TinyBlog\common\models\Cate;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\TinyBlog
 */
class Upgrade extends Migration implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '1.0.0', // 默认版本
        '1.0.1',
        '1.0.2',
    ];

    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        switch ($addon->version) {
            case '1.0.2' :
                $models = Cate::find()->select(['id', 'tree'])->asArray()->all();
                foreach ($models as $model) {
                    $this->updateTree($model['id'], $model['tree'], Cate::class);
                }
                break;
            case '1.0.1' :
                $this->addColumn('{{%addon_tiny_blog_tag}}', 'frequency', "int(10) NULL DEFAULT '0' COMMENT '使用次数'");
                break;
        }
    }

    /**
     * @param int $id
     * @param string $tree
     * @param $model
     * @return void
     */
    protected function updateTree($id, $tree, $model)
    {
        $tree = StringHelper::replace(' ', '', $tree);
        $endTree = substr($tree, strlen($tree) - 1);
        if ($endTree != '-') {
            $tree = $tree.'-';
            $model::updateAll(['tree' => $tree], ['id' => $id]);
        }
    }
}
