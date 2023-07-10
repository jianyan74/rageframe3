<?php

namespace common\traits;

use Yii;
use yii\helpers\Json;
use yii\base\InvalidConfigException;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\enums\NotifyConfigTypeEnum;

/**
 * Trait NotifyConfigTrait
 * @package common\traits
 */
trait NotifyConfigTrait
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('"modelClass" 属性必须设置.');
        }

        if ($this->viewPrefix === null) {
            throw new InvalidConfigException('"viewPrefix" 属性必须设置.');
        }
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $nameMap = $this->getNameMap();
        $typeMap = $this->getTypeMap();

        $models = $this->modelClass::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'name', array_keys($nameMap)])
            ->andFilterWhere(['addon_name' => Yii::$app->params['addon']['name'] ?? ''])
            ->andWhere(['app_id' => $this->getAppId()])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();

        $default = new $this->modelClass;
        $default = ArrayHelper::toArray($default->loadDefaultValues());

        $data = [];
        foreach ($nameMap as $name => $value) {
            if (!isset($data[$name])) {
                $data[$name] = [
                    'name' => $name,
                    'value' => $value,
                    'params' => []
                ];
            }

            foreach ($typeMap as $key => $item) {
                $default['status'] = StatusEnum::DISABLED;
                if (in_array($key, [NotifyConfigTypeEnum::SYS])) {
                    $default['status'] = StatusEnum::ENABLED;
                }

                $data[$name]['params'][$key] = $default;
            }


            foreach ($models as $model) {
                if ($name == $model['name'] && in_array($model['type'], array_keys($typeMap))) {
                    $data[$name]['params'][$model['type']] = $model;
                }
            }
        }

        return $this->render($this->viewPrefix . $this->action->id, [
            'data' => $data,
            'nameMap' => $nameMap,
            'typeMap' => $typeMap,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $name = Yii::$app->request->get('name');
        $type = Yii::$app->request->get('type');
        $model = $this->findModel($name, $type);
        try {
            if (!empty($model->content)) {
                $content = Json::decode($model->content);
                $model->content = $content;
            }
        } catch (\Exception $e) {
        }

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->content = is_array($model->content) ? Json::encode($model->content) : $model->content;

            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->viewPrefix . $type, [
            'model' => $model,
            'typeMap' => $this->getTypeMap(),
            'nameMap' => $this->getNameMap(),
        ]);
    }

    /**
     * 辅助表格信息
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionHelpTable()
    {
        $name = Yii::$app->request->get('name');
        $default = $this->getNameDefaultData($name);
        $tables = $default['tables'];
        $data = [];
        foreach ($tables as $table) {
            $tableSchema = Yii::$app->db->getTableSchema($table['tableName']);
            $table['fields'] = [];
            foreach ($tableSchema->columns as $column) {
                // 过滤不显示的字段
                if (isset($table['filterFields']) && in_array($column->name, $table['filterFields'])) {
                    continue;
                }

                $table['fields'][] = [
                    'name' => '{' . $table['prefix'] . '.' . $column->name . '}',
                    'comment' => $column->comment,
                ];
            }

            $data[] = $table;
        }

        return $this->renderAjax($this->viewPrefix . $this->action->id, [
            'data' => $data,
            'name' => $name,
            'nameMap' => $this->getNameMap(),
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($name, $type)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($model = $this->modelClass::find()->where([
            'name' => $name,
            'type' => $type,
            'merchant_id' => $this->getMerchantId(),
        ])
            ->one())) {
            $model = new $this->modelClass;
            $model = $model->loadDefaultValues();
            $model->name = $name;
            $model->type = $type;
            if (in_array($type, [NotifyConfigTypeEnum::APP_PUSH, NotifyConfigTypeEnum::SYS])) {
                $model->attributes = $this->getNameDefaultData($name);
            }

            if (in_array($type, [NotifyConfigTypeEnum::SYS])) {
                $model->status = StatusEnum::ENABLED;
            }
        }

        $model->app_id = $this->getAppId();
        $model->addon_name = Yii::$app->params['addon']['name'] ?? '';
        !empty($model->addon_name) && $model->is_addon = StatusEnum::ENABLED;
        empty($model->params) && $model->params = [];

        return $model;
    }

    /**
     * @return string
     */
    protected function getAppId()
    {
        return Yii::$app->id;
    }

    /**
     * @return array|string[]
     */
    protected function getTypeMap()
    {
        return NotifyConfigTypeEnum::getMap();
    }
}
