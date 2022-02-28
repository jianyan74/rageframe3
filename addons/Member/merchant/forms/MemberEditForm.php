<?php

namespace addons\Member\merchant\forms;

use Yii;
use common\helpers\ArrayHelper;
use common\forms\MemberForm;

/**
 * Class MemberEditForm
 * @package addons\Member\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MemberEditForm extends MemberForm
{
    public $tags = [];

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['tags', 'safe'],
        ]);
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'tags' => '标签'
        ]);
    }

    public function afterFind()
    {
        $this->tags = Yii::$app->services->memberTagMap->findIdsByMemberId($this->id);

        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->services->memberTagMap->addTags($this->id, $this->tags);

        parent::afterSave($insert, $changedAttributes);
    }
}
