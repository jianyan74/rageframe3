<?php

namespace services\common;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\common\Attachment;

/**
 * Class AttachmentService
 * @package services\common
 */
class AttachmentService extends Service
{
    /**
     * @param $data
     * @return Attachment
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function create($data)
    {
        $model = new Attachment();
        $model->attributes = $data;
        !$model->save() && $this->error($model);

        return $model;
    }

    /**
     * 查询校验
     *
     * @param $md5
     * @param string $drive
     * @param int $cate_id
     * @param string $upload_type
     * @return array|false
     */
    public function findByMd5($md5, $drive = '', $cate_id = 0, $upload_type = '')
    {
        $model = Attachment::find()
            ->where(['md5' => $md5])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['drive' => $drive])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();

        if ($model) {
            !empty($cate_id) && $model->cate_id = $cate_id;
            $model->upload_type = $upload_type;
            $model->updated_at = time();
            $model->save();

            return $model->toArray();
        }

        return false;
    }

    /**
     * 获取百度编辑器查询数据
     *
     * @param $uploadType
     * @param $offset
     * @param $limit
     * @return array
     */
    public function baiduListPage($uploadType, $offset, $limit)
    {
        $data = Attachment::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['upload_type' => $uploadType])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc');
        $countModel = clone $data;
        $models = $data->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();

        $files = [];
        foreach ($models as $model) {
            $files[] = [
                'url' => $model['url'],
                'mtime' => $model['created_at'],
            ];
        }

        return [$files, $countModel->count()];
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Attachment::find()
            ->where(['id' => $id])
            ->one();
    }
}
