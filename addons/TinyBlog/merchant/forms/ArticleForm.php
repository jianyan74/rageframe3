<?php

namespace addons\TinyBlog\merchant\forms;

use Yii;
use addons\TinyBlog\common\models\Article;
use common\helpers\ArrayHelper;

/**
 * Class ArticleForm
 * @package addons\TinyBlog\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleForm extends Article
{
    public $tags = [];

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['tags', 'safe']
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'tags' => '标签'
        ]);
    }

    /**
     * 生成推荐位的值
     * @return int|mixed
     */
    protected function getPosition()
    {
        $position = $this->position;
        $pos = 0;
        if (!is_array($position)) {
            if ($position > 0) {
                return $position;
            }
        } else {
            foreach ($position as $key => $value) {
                // 将各个推荐位的值相加
                $pos += $value;
            }
        }

        return $pos;
    }

    public function beforeSave($insert)
    {
        // 推荐位
        $this->position = $this->getPosition();

        return parent::beforeSave($insert);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->tinyBlogService->tagMap->create($this->id, $this->tags);

        parent::afterSave($insert, $changedAttributes);
    }
}
