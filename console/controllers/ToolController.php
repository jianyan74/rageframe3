<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\Migration;

/**
 * 小工具
 *
 * Class ToolController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ToolController extends Controller
{
    /**
     * 批量修改字段类型
     *
     *  ./yii tool/shop-to-store
     *
     * @return void
     */
    public function actionShopToStore()
    {
        $migration = new Migration();
        // 表列表
        $tables = array_map('array_change_key_case', Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll());
        foreach ($tables as $table) {
            $migration->renameColumn($table['name'], 'shop_id', 'store_id');
        }
    }
}
