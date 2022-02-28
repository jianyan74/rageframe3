<?php

namespace addons\RfDemo\services;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use addons\RfDemo\common\models\Cate;

/**
 * Class CateService
 * @package addons\RfDemo\services
 * @author jianyan74 <751393839@qq.com>
 */
class CateService
{
    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($id = '')
    {
        $list = Cate::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级分类'], $data);
    }

    /**
     * @param string $pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findById($id)
    {
        return Cate::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->one();
    }

    /**
     * @param string $pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        $merchant_id = Yii::$app->services->merchant->getNotNullId();

        return Cate::find()
            ->select(['id', 'title', 'pid', 'level'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => $merchant_id])
            ->orderBy('sort asc, id desc')
            ->asArray()
            ->all();
    }
}
