<?php

namespace addons\Member\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\enums\AccessTokenGroupEnum;
use common\enums\CreditsLogTypeEnum;
use common\enums\GenderEnum;
use common\forms\MemberForm as Member;
use common\helpers\BcHelper;
use common\helpers\ExcelHelper;
use common\helpers\ResultHelper;
use common\traits\MemberMobileSelect;
use common\traits\MerchantCurd;
use common\models\member\CreditsLog;
use common\models\member\Account;
use addons\Member\merchant\forms\RechargeForm;
use addons\Member\merchant\forms\MemberEditForm;
use addons\Member\merchant\forms\MemberCreateForm;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * 会员管理
 *
 * Class MemberController
 * @package addons\Member\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use MerchantCurd, MemberMobileSelect;

    /**
     * @var Member
     */
    public $modelClass = Member::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $startTime = Yii::$app->request->get('start_time');
        $endTime = Yii::$app->request->get('end_time');

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere([
                'type' => MemberTypeEnum::MEMBER,
                'status' => StatusEnum::ENABLED
            ])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['between', 'created_at', !empty($startTime) ? strtotime($startTime) : '', !empty($endTime) ? strtotime($endTime) : ''])
            ->with(['account', 'memberLevel', 'tag']);

        $models = $dataProvider->getModels();
        $pageAccountTotal = [
            'user_money' => 0,
            'user_integral' => 0,
            'user_growth' => 0,
        ];
        foreach ($models as $model) {
            $pageAccountTotal['user_money'] = BcHelper::add($pageAccountTotal['user_money'], $model->account->user_money);
            $pageAccountTotal['user_integral'] = BcHelper::add($pageAccountTotal['user_integral'], $model->account->user_integral);
            $pageAccountTotal['user_growth'] = BcHelper::add($pageAccountTotal['user_growth'], $model->account->user_growth);
        }

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'pageAccountTotal' => $pageAccountTotal,
            'levelMap' => Yii::$app->services->memberLevel->getMap(),
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findEditForm($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->referrer();
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findCreateForm($id);
        $model->merchant_id = Yii::$app->services->merchant->getNotNullId();
        $model->type = MemberTypeEnum::MEMBER;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberUpdate', '更新用户账号密码', 0, [], false);

            // 验证密码
            if (!empty($model->password_hash)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }

            return $model->save()
                ? $this->message('操作成功', $this->redirect(Yii::$app->request->referrer))
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->password_hash = '';
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $type = Yii::$app->request->get('type', CreditsLogTypeEnum::USER_MONEY);

        $searchModel = new SearchModel([
            'model' => CreditsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['member_id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type, 'member_type' => MemberTypeEnum::MEMBER])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['member']);

        return $this->render($this->action->id, [
            'member' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' => $type,
            'id' => $id,
        ]);
    }

    /**
     * 修改等级
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionUpdateLevel()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberUpdateLevel', '更新用户等级');

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'levelMap' => Yii::$app->services->memberLevel->getMap(),
        ]);
    }

    /**
     * 积分/余额变更
     *
     * @param $id
     * @return mixed|string
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRecharge($id)
    {
        $rechargeForm = new RechargeForm();
        $member = Yii::$app->services->member->findById($id);

        // ajax 校验
        $this->activeFormValidate($rechargeForm);
        if ($rechargeForm->load(Yii::$app->request->post())) {
            // 记录行为
            Yii::$app->services->actionLog->create('memberRecharge', '更新用户余额/积分/成长值');

            if (!$rechargeForm->save($member)) {
                return $this->message($this->getError($rechargeForm), $this->redirect(Yii::$app->request->referrer), 'error');
            }

            return $this->message('充值成功', $this->redirect(Yii::$app->request->referrer));
        }

        return $this->renderAjax($this->action->id, [
            'model' => $member,
            'rechargeForm' => $rechargeForm,
        ]);
    }

    /**
     * 黑名单
     *
     * @param $id
     * @return mixed
     */
    public function actionBlacklist($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model->status = StatusEnum::DISABLED;
        if ($model->save()) {
            return $this->message("拉入黑名单成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("拉入黑名单失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }

    /**
     * 全部备货完成
     *
     * @param $id
     * @return mixed|string
     */
    public function actionImportMember()
    {
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $file = $_FILES['excelFile'];
                if (empty($file['tmp_name'])) {
                    return $this->message('请上传需要导入的文件', $this->redirect(['member/index']), 'error');
                }

                $defaultData = ExcelHelper::import($file['tmp_name'], 2);
                foreach ($defaultData as $datum) {
                    $member = new Member();
                    $member->merchant_id = Yii::$app->services->merchant->getNotNullId();
                    $member->nickname = $datum[0];
                    $member->mobile = $datum[1];
                    $member->password = $datum[2];
                    $member->realname = $datum[3];
                    $member->gender = $datum[4] == '男' ? GenderEnum::MAN : GenderEnum::WOMAN;
                    $member->head_portrait = $datum[5];
                    $member->qq = $datum[6];
                    $member->email = $datum[7];
                    $member->birthday = date('Y-m-d', Date::excelToTimestamp($datum[8]));
                    $member->source = AccessTokenGroupEnum::EXCEL_IMPORT;
                    $member->save();

                    Account::updateAll(['user_money' => (float)$datum[9], 'user_integral' => (int)$datum[10], 'user_growth' => (int)$datum[11]], ['member_id' => $member->id]);
                }

                $transaction->commit();

                return $this->message('导入成功', $this->redirect(['member/index']));
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ResultHelper::json(422, $e->getMessage());
            }
        }

        return $this->renderAjax($this->action->id);
    }

    /**
     * 下载模板
     */
    public function actionTemplateDownload()
    {
        $path = Yii::getAlias('@addons') . '/Member/common/file/member.xls';

        Yii::$app->response->sendFile($path, '一键导入会员模板_' . time() . '.xls');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findCreateForm($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = MemberCreateForm::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new MemberCreateForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findEditForm($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = MemberEditForm::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()])))) {
            $model = new MemberEditForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
