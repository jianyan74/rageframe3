<?php

namespace addons\RfDevTool\common\queues;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use common\helpers\StringHelper;
use common\models\common\Provinces;
use linslin\yii2\curl\Curl;
use QL\QueryList;
use addons\RfDevTool\common\models\ProvinceGatherLog;

/**
 * Class ProvinceChildJob
 * @package addons\RfDevTool\common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceChildJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var int
     */
    public $maxLevel;

    /**
     * @var array
     */
    public $parent;

    /**
     * @var int
     */
    public $job_id;

    /**
     * 重连次数
     *
     * @var int
     */
    public $reconnection = 5;

    /**
     * @var int
     */
    public $level = 2;

    /**
     * 路径前缀
     *
     * @var
     */
    public $chlidPrefix;

    /**
     * @var string[]
     */
    public $range = [
        2 => 'table.citytable td+td',
        3 => 'table.countytable td+td',
        4 => 'table.towntable td+td',
        5 => 'table.villagetable .villagetr',
    ];



    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     */
    public function execute($queue)
    {
        /** @var QueryList $ql */
        $ql = QueryList::getInstance();
        // 注册一个myHttp方法到QueryList对象
        $ql->bind('http', function ($url) {
            $curl = new Curl();
            $html = $curl->get($url);
            $encode = mb_detect_encoding($html, ["ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5']);
            $str_encode = mb_convert_encoding($html, 'UTF-8', $encode);
            $this->setHtml($str_encode);
            return $this;
        });

        /******************************** 社区 ********************************/

        // 东莞市、中山市、儋州市下面直接是镇所以规则要变
        if (isset($this->parent['code'][1])) {
            if ($this->parent['code'][1] >= 441900000 && $this->parent['code'][1] < 443000000) {
                // 社区
                $this->getVillage($ql);
                return;
            }

            if ($this->parent['code'][1] >= 460400000 && $this->parent['code'][1] < 460500000) {
                // 社区
                $this->getVillage($ql);
                return;
            }
        }

        if ($this->level >= 5) {
            // 社区
            $this->getVillage($ql);
            return;
        }

        /******************************** 市区县/街道 ********************************/

        $level = $this->level;
        // 东莞市、中山市、儋州市下面直接是镇所以规则要变
        if (isset($this->parent['code'][1]) && in_array($this->parent['code'][1], [4419, 4420, 4604])) {
            $level += 1;
        }

        $this->getCityCountyTown($ql, $level);
    }

    /**
     * 获取社区
     *
     * @param QueryList $ql
     */
    public function getVillage($ql)
    {
        $data = $ql->rules([
            'id' => ['td:first', 'text'],
            'title' => ['td:nth-child(3)', 'text']
        ])->http($this->parent['chlidLink'])->range($this->range[5])->query()->getData()->all();

        // 找不到数据库可能是抓取失败重新连接
        if (empty($data)) {
            $this->reconnection();
            return;
        }

        foreach ($data as &$datum) {
            if (empty($datum['id']) && empty($datum['title'])) {
                continue;
            }

            $datum['level'] = $this->parent['level'] + 1;
            $datum['pid'] = $this->parent['id'];
            $datum['tree'] = $this->parent['tree'] . $datum['pid'] . '-';

            // 写入数据库
            if (!($model = Provinces::findOne(['id' => $datum['id']]))) {
                $model = new Provinces();
            }
            $model->attributes = $datum;
            $model->save();
        }
    }

    /**
     * 获取市区街道/县
     *
     * @param QueryList $ql
     */
    public function getCityCountyTown($ql, $level)
    {
        $data = $ql->rules([
            'title' => ['a', 'text'],
            'link' => ['a', 'href']
        ])->http($this->parent['chlidLink'])->range($this->range[$level])->query()->getData()->all();
        $codeSuffix = $this->level == 2 ? '00' : '';

        // 找不到数据库可能是抓取失败重新连接
        if (empty($data)) {
            $this->reconnection();
            return;
        }

        foreach ($data as &$datum) {
            if (empty($datum['text']) && empty($datum['link'])) {
                continue;
            }

            $code = StringHelper::replace('.html', '', $datum['link']);
            $datum['code'] = explode('/', $code);
            // 地址前缀
            $chlidPrefix = $this->chlidPrefix;
            if (empty($chlidPrefix)) {
                $chlidPrefix = $datum['code'][0];
            } else {
                $chlidPrefix = $chlidPrefix . '/' . $datum['code'][0];
            }

            $datum['id'] = $datum['code'][1] . $codeSuffix;
            $datum['level'] = $this->parent['level'] + 1;
            $datum['pid'] = $this->parent['id'];
            $datum['tree'] = $this->parent['tree'] . $datum['pid'] . '-';
            $datum['chlidPrefix'] = $chlidPrefix;
            $datum['chlidLink'] = $this->baseUrl . $chlidPrefix . '/' . $datum['code'][1] . '.html';

            // 写入数据库
            if (!($model = Provinces::findOne(['id' => $datum['id']]))) {
                $model = new Provinces();
            }
            $model->attributes = $datum;
            $model->save();

            if ($datum['level'] + 1 <= $this->maxLevel) {
                $this->createJob($datum);
            }
        }
    }

    /**
     * 重连
     */
    protected function reconnection()
    {
        if ($this->reconnection <= 0) {
            $this->log('采集彻底失败');
            return;
        }

        $queue = new ProvinceChildJob([
            'parent' => $this->parent,
            'baseUrl' => $this->baseUrl,
            'maxLevel' => $this->maxLevel,
            'level' => $this->level,
            'job_id' => $this->job_id,
            'reconnection' => $this->reconnection - 1,
        ]);

        // 延迟60秒再运行
        $messageId = Yii::$app->queue->delay(1 * 60)->push($queue);
        $this->log('采集失败,等待重试时间60秒', $messageId);
    }

    /**
     * 记录日志
     */
    protected function log($remark, $message_id = 0)
    {
        $model = new ProvinceGatherLog();
        $model->data = $this->parent;
        $model->url = $this->baseUrl;
        $model->max_level = $this->maxLevel;
        $model->level = $this->level;
        $model->job_id = $this->job_id;
        $model->message_id = $message_id;
        $model->reconnection = $this->reconnection;
        $model->remark = $remark;
        $model->save();
        if (!$model->save()) {
            Yii::error(Yii::$app->services->base->analysisErr($model->getFirstErrors()));
        }
    }

    /**
     * 创建一个新队列
     *
     * @param $datum
     * @param $level
     */
    protected function createJob($datum)
    {
        $queue = new ProvinceChildJob([
            'parent' => $datum,
            'baseUrl' => $this->baseUrl,
            'chlidPrefix' => $datum['chlidPrefix'],
            'maxLevel' => $this->maxLevel,
            'level' => $this->level + 1,
            'job_id' => $this->job_id,
        ]);

        $messageId = Yii::$app->queue->push($queue);
    }
}
