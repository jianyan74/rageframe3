<?php

namespace common\widgets\webuploader;

use common\helpers\ArrayHelper;
use common\models\common\AttachmentCate;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\enums\StatusEnum;
use common\traits\BaseAction;
use common\traits\MerchantCurd;
use common\models\common\Attachment;
use common\enums\AttachmentUploadTypeEnum;
use common\helpers\ResultHelper;
use common\helpers\UploadHelper;
use common\traits\FileAction;

/**
 * Class FilesController
 * @package common\widgets\webuploader
 */
class FilesController extends Controller
{
    use BaseAction, MerchantCurd, FileAction;

    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/main";

    /**
     * @var Attachment
     */
    public $modelClass = Attachment::class;

    /**
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        if (YII_DEBUG) {
            return [];
        }

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * 资源选择器
     *
     * @param bool $json
     * @return array|string
     */
    public function actionSelector()
    {
        $this->layout = '@backend/views/layouts/blank';

        $pageSize = Yii::$app->request->get('per-page', 10);
        $uploadType = Yii::$app->request->get('upload_type', AttachmentUploadTypeEnum::IMAGES);
        $year = Yii::$app->request->get('year', '');
        $month = Yii::$app->request->get('month', '');
        $keyword = Yii::$app->request->get('keyword', '');
        $drive = Yii::$app->request->get('drive', '');
        $cateId = Yii::$app->request->get('cate_id', '');

        $data = Attachment::find()
            ->where(['status' => StatusEnum::ENABLED, 'upload_type' => $uploadType])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['store_id' => $this->getStoreId()])
            ->andFilterWhere(['drive' => $drive])
            ->andFilterWhere(['year' => $year])
            ->andFilterWhere(['month' => $month])
            ->andFilterWhere(['cate_id' => $cateId])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('updated_at desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        // 数量
        $groupByCateId = Attachment::find()
            ->select(['count(id) as count', 'cate_id'])
            ->where(['status' => StatusEnum::ENABLED, 'upload_type' => $uploadType])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['store_id' => $this->getStoreId()])
            ->groupBy('cate_id')
            ->asArray()
            ->all();

        $cateCountMap = ArrayHelper::map($groupByCateId, 'cate_id', 'count');

        // 如果是以文件形式上传的图片手动修改为图片类型显示
        foreach ($models as &$model) {
           $model['base_url'] = $model['url'];
           $model['upload_type'] = UploadHelper::formattingFileType($model['specific_type'], $model['extension'], $model['upload_type']);
        }

        // 选择上传类型
        if (empty($uploadDrive = Yii::$app->request->get('upload_drive'))) {
            $uploadDrive = Yii::$app->params['uploadConfig'][$uploadType]['drive'];
        }

        // 判断是否直接返回json格式(百度编辑器)
        if (Yii::$app->request->get('json') == true) {
            return ResultHelper::json(200, '获取成功', $models);
        }

        // 装修
        if (Yii::$app->request->get('decorate') == true) {
            return ResultHelper::json(200, '获取成功', [
                'list' => $models,
                'pages' => [
                    'totalCount' => $pages->totalCount,
                    'pageSize' => $pages->pageSize,
                ]
            ]);
        }

        return $this->render('@common/widgets/webuploader/views/selector', [
            'models' => $models,
            'pages' => $pages,
            'uploadType' => $uploadType,
            'uploadDrive' => $uploadDrive,
            'multiple' => Yii::$app->request->get('multiple', true),
            'boxId' => Yii::$app->request->get('box_id'),
            'cates' => Yii::$app->services->attachmentCate->findAll($uploadType),
            'cateId' => $cateId,
            'cateCountMap' => $cateCountMap,
            'year' => $year,
            'month' => $month,
            'keyword' => $keyword,
            'drive' => $drive,
        ]);
    }

    /**
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        $model = Yii::$app->services->attachment->findById($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->message('操作成功，请切换到第一页查看', $this->redirect(Yii::$app->request->referrer))
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('@common/widgets/webuploader/views/ajax-edit', [
            'model' => $model,
            'cateMap' => Yii::$app->services->attachmentCate->getMap($type),
        ]);
    }

    /**
     * @return array|mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionCateAjaxEdit()
    {
        $type = Yii::$app->request->get('type');
        $model = new AttachmentCate();
        $model->loadDefaultValues();
        $model->type = $type;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->message('操作成功', $this->redirect(Yii::$app->request->referrer))
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('@common/widgets/webuploader/views/cate-ajax-edit', [
            'model' => $model,
            'type' => $type,
        ]);
    }
}
