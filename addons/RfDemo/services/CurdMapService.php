<?php

namespace addons\RfDemo\services;

use addons\RfDemo\common\models\CurdMap;

/**
 * Class CurdMapService
 * @package addons\RfDemo\services
 * @author jianyan74 <751393839@qq.com>
 */
class CurdMapService
{
    /**
     * 查询在某个区域范围内
     *
     *   Yii::$app->rfDemoService->curdMap->findByLngLat(116.456270, 39.919990);
     *
     * @param $longitude
     * @param $latitude
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByLngLat($longitude, $latitude)
    {
        return CurdMap::find()
            ->select(['id', 'merchant_id', 'curd_id', 'name'])
            ->where("MBRWithin (ST_GeomFromText('POINT(" . $longitude . " " . $latitude .")'), polygon)")
            ->asArray()
            ->all();
    }
}
