<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\helpers\ArrayHelper;
use common\helpers\ResultHelper;
use addons\Wechat\common\models\Fans;
use addons\Wechat\common\models\FansTagMap;
use addons\Wechat\common\enums\FansFollowEnum;

/**
 * Class FansController
 * @package addons\Wechat\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class FansController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Fans
     */
    public $modelClass = Fans::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['nickname'], // 模糊查询
            'relations' => ['tags' => ['tag_id']], // 关联 tags 表的 tag_id 字段
            'defaultOrder' => [
                'follow_time' => SORT_DESC,
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['auth']);

        $tags = Yii::$app->wechatService->fansTags->getList();
        $params = Yii::$app->request->get('SearchModel');

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tagId' => $params['tags.tag_id'] ?? 0,
            'fansCount' => Yii::$app->wechatService->fans->findFollowCount(),
            'fansTags' => $tags,
            'allTag' => ArrayHelper::map($tags, 'id', 'name'),
        ]);
    }

    /**
     * 备注
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->wechat->app->user->remark($model->openid, $model->remark);

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 发送消息
     *
     * @param $id
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionSendMessage($openid)
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            try {
                $media_id = $data[$data['type']] ?? $data['content'];
                Yii::$app->wechatService->message->customer($openid, $data['type'], $media_id);
                return ResultHelper::json(200, '发送成功');
            } catch (\Exception $e) {
                return ResultHelper::json(422, $e->getMessage());
            }
        }

        return $this->renderAjax('send-message', [
            'model' => Yii::$app->wechatService->fans->findByOpenId($openid)
        ]);
    }

    /**
     * 贴标签
     *
     * @param $fan_id
     * @return string|Response
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionMoveTag($fan_id)
    {
        $fans = Yii::$app->wechatService->fans->findByIdWithTag($fan_id);

        // 用户当前标签
        $fansTags = array_column($fans['tags'], 'tag_id');
        if (Yii::$app->request->isPost) {
            $tags = Yii::$app->request->post('tag_id', []);
            FansTagMap::deleteAll(['fans_id' => $fan_id]);
            // 添加标签
            foreach ($tags as $tag_id) {
                !in_array($tag_id, $fansTags) && Yii::$app->wechat->app->user_tag->tagUsers([$fans['openid']], $tag_id);

                $model = new FansTagMap();
                $model->fans_id = $fan_id;
                $model->tag_id = $tag_id;
                $model->save();
            }

            // 移除标签
            foreach ($fansTags as $tag_id) {
                !in_array($tag_id, $tags) && Yii::$app->wechat->app->user_tag->untagUsers([$fans['openid']], $tag_id);
            }

            // 更新标签
            Yii::$app->wechatService->fansTags->getList(true);

            return $this->redirect(['index']);
        }

        return $this->renderAjax('move-tag', [
            'tags' => Yii::$app->wechatService->fansTags->getList(),
            'fansTags' => $fansTags,
        ]);
    }

    /**
     * 获取全部粉丝的  openid
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSyncAllOpenid()
    {
        $nextOpenid = Yii::$app->request->get('next_openid', '');
        // 设置关注全部为为关注
        empty($nextOpenid) && Fans::updateAll(['follow' => FansFollowEnum::OFF], ['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);

        try {
            list($total, $count, $nextOpenid) = Yii::$app->wechatService->fans->syncAllOpenid($nextOpenid);

            return ResultHelper::json(200, '同步粉丝 openid 完成', [
                'total' => $total,
                'count' => $count,
                'next_openid' => $nextOpenid,
            ]);
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * 开始同步粉丝数据
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws yii\db\Exception
     */
    public function actionSync()
    {
        $type = Yii::$app->request->post('type', 'all');
        $page = Yii::$app->request->post('page', 0);

        // 全部同步
        if ($type == 'all' && !empty($models = Yii::$app->wechatService->fans->findFollowByPage($page))) {
            // 同步粉丝信息
            foreach ($models as $fans) {
                Yii::$app->wechatService->fans->syncByOpenid($fans['openid']);
            }

            return ResultHelper::json(200, '同步完成', [
                'page' => $page + 1
            ]);
        }

        // 选中同步
        if ($type == 'check') {
            if (empty($openIds = Yii::$app->request->post('openids')) || !is_array($openIds)) {
                return ResultHelper::json(404, '请选择粉丝');
            }

            // 系统内的粉丝
            if (!empty($syncFans = Yii::$app->wechatService->fans->findByOpenids($openIds))) {
                // 同步粉丝信息
                foreach ($syncFans as $fans) {
                    Yii::$app->wechatService->fans->syncByOpenid($fans['openid']);
                }
            }
        }

        return ResultHelper::json(200, '同步完成');
    }

    /**
     * @return array|mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionSyncInfo()
    {
        if (!empty($syncFans = Yii::$app->wechatService->fans->findNotFollowTime())) {
            // 同步粉丝信息
            foreach ($syncFans as $fans) {
                Yii::$app->wechatService->fans->syncByOpenid($fans['openid']);
            }

            return ResultHelper::json(200, '同步完成', ArrayHelper::map($syncFans, 'id', 'openid'));
        }

        return ResultHelper::json(201, '同步完成');
    }
}
