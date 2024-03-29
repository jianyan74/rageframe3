<?php

namespace addons\Wechat\services;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\AddonHelper;
use common\helpers\ExecuteHelper;
use common\helpers\ArrayHelper;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use addons\Wechat\common\models\Rule;
use addons\Wechat\common\models\RuleKeyword;
use addons\Wechat\common\enums\RuleModuleEnum;
use addons\Wechat\common\enums\RuleKeywordTypeEnum;

/**
 * Class RuleKeywordService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class RuleKeywordService extends Service
{
    /**
     * 关键字查询匹配
     *
     * @param $content
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function match($content)
    {
        $keyword = RuleKeyword::find()->where([
            'or',
            ['and', '{{type}} = :typeMatch', '{{content}} = :content'], // 直接匹配关键字
            ['and', '{{type}} = :typeInclude', 'INSTR(:content, {{content}}) > 0'], // 包含关键字
            ['and', '{{type}} = :typeRegular', ' :content REGEXP {{content}}'], // 正则匹配关键字
        ])->addParams([
            ':content' => $content,
            ':typeMatch' => RuleKeywordTypeEnum::MATCH,
            ':typeInclude' => RuleKeywordTypeEnum::INCLUDE,
            ':typeRegular' => RuleKeywordTypeEnum::REGULAR
        ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('sort desc,id desc')
            ->one();

        if (!$keyword) {
            return false;
        }

        // 查询直接接管的
        $takeKeyword = RuleKeyword::find()
            ->where(['type' => RuleKeywordTypeEnum::TAKE, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['>=', 'sort', $keyword->sort])
            ->orderBy('sort desc, id desc')
            ->one();
        $takeKeyword && $keyword = $takeKeyword;

        // 历史消息记录
        Yii::$app->params['messageHistory'] = ArrayHelper::merge(Yii::$app->params['messageHistory'], [
            'keyword_id' => $keyword->id,
            'rule_id' => $keyword->rule_id,
            'module' => $keyword->module,
        ]);

        /* @var $model Rule */
        $model = Rule::find()
            ->where(['id' => $keyword->rule_id])
            ->one();

        switch ($keyword->module) {
            // 文字回复
            case  RuleModuleEnum::TEXT :
                return new Text($model->data);
                break;
            // 图文回复
            case  RuleModuleEnum::NEWS :
                $news = $model->news;
                $newsList = [];
                if (!$news) {
                    return false;
                }
                foreach ($news as $vo) {
                    $newsList[] = new NewsItem([
                        'title' => $vo['title'],
                        'description' => $vo['digest'],
                        'url' => $vo['media_url'],
                        'image' => $vo['thumb_url'],
                    ]);
                }

                return new News($newsList);
                break;
            // 图片回复
            case  RuleModuleEnum::IMAGE :
                return new Image($model->data);
                break;
            // 视频回复
            case RuleModuleEnum::VIDEO :
                return new Video($model->data, [
                    'title' => $model->attachment->title,
                    'description' => $model->attachment->description,
                ]);
                break;
            // 语音回复
            case RuleModuleEnum::VOICE :
                return new Voice($model->data);
                break;
            // 自定义接口回复
            case RuleModuleEnum::USER_API :
                if ($apiContent = Yii::$app->wechatService->rule->getApiData($model,
                    Yii::$app->wechatService->message->getMessage())) {
                    return $apiContent;
                }

                return $model->default;
                break;
            // 模块回复
            case RuleModuleEnum::ADDON :
                // Yii::$app->params['messageHistory']['addon_name'] = $model->data;
                // $class = AddonHelper::getAddonMessage($model->data);
                // return ExecuteHelper::map($class, 'run', Yii::$app->wechatService->message->getMessage());
                break;
            default :
                return false;
                break;
        }
    }

    /**
     * 验证是否有直接接管
     *
     * @param $ruleKeyword
     * @return bool
     */
    public function verifyTake($ruleKeyword)
    {
        foreach ($ruleKeyword as $item) {
            if ($item->type == RuleKeywordTypeEnum::TAKE) {
                return true;
            }
        }

        return false;
    }

    /**
     * 更新关键字
     *
     * @param $rule
     * @param $ruleKeywords
     * @param $defaultRuleKeywords
     * @throws \yii\db\Exception
     */
    public function update($rule, $ruleKeywords, $defaultRuleKeywords)
    {
        // 判断是否有直接接管
        if (!isset($ruleKeywords[RuleKeywordTypeEnum::TAKE])) {
            RuleKeyword::deleteAll(['rule_id' => $rule->id, 'type' => RuleKeywordTypeEnum::TAKE]);
        }

        // 给关键字赋值默认值
        foreach (RuleKeywordTypeEnum::getMap() as $key => $value) {
            !isset($ruleKeywords[$key]) && $ruleKeywords[$key] = [];
        }

        $rows = [];

        $merchant_id = Yii::$app->services->merchant->getNotNullId();
        $store_id = Yii::$app->services->store->getNotNullId();
        foreach ($ruleKeywords as $key => $vo) {
            // 去重
            $keyword = array_unique($vo);

            // 删除不存在的关键字
            if ($diff = array_diff($defaultRuleKeywords[$key], $keyword)) {
                RuleKeyword::deleteAll([
                    'and',
                    ['rule_id' => $rule->id],
                    ['type' => $key],
                    ['in', 'content', array_values($diff)]
                ]);
            }
            // 判断是否有更改不更改直接不插入
            if (empty($keyword = array_diff($keyword, $defaultRuleKeywords[$key]))) {
                $keyword = [];
            }

            // 插入数据
            foreach ($keyword as $content) {
                $rows[] = [$rule->id, $rule->module, $content, $rule->sort, $rule->status, $key, $merchant_id, $store_id];
            }
        }

        // 插入数据
        $field = ['rule_id', 'module', 'content', 'sort', 'status', 'type', 'merchant_id', 'store_id'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(RuleKeyword::tableName(), $field, $rows)->execute();
    }

    /**
     * @param string $fields
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($fields = 'id, content')
    {
        return RuleKeyword::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select($fields)
            ->asArray()
            ->all();
    }

    /**
     * 获取规则关键字类别
     *
     * @param array $ruleKeyword
     * @return array
     */
    public function getType($ruleKeyword)
    {
        !$ruleKeyword && $ruleKeyword = [];

        // 关键字列表
        $ruleKeywords = [
            RuleKeywordTypeEnum::MATCH => [],
            RuleKeywordTypeEnum::REGULAR => [],
            RuleKeywordTypeEnum::INCLUDE => [],
            RuleKeywordTypeEnum::TAKE => [],
        ];

        foreach ($ruleKeyword as $value) {
            $ruleKeywords[$value['type']][] = $value['content'];
        }

        return $ruleKeywords;
    }
}
