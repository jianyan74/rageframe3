<?php

namespace services\common;

use Yii;
use common\helpers\ArrayHelper;
use common\models\common\Theme;
use common\enums\ThemeColorEnum;

/**
 * Class ThemeService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ThemeService
{
    /**
     * @return void
     */
    public function autoSwitcher()
    {
        if ($theme = $this->findByMemberId(Yii::$app->user->id)) {
            Yii::$app->params['theme'] = ArrayHelper::merge(Yii::$app->params['theme'], [
                'layout' => $theme->layout,
                'color' => $theme->color,
            ]);
        }
    }

    /**
     * @param $layout
     * @param $color
     * @return void
     */
    public function update($layout, $color = ThemeColorEnum::BLACK)
    {
        $memberId = Yii::$app->user->id;
        $theme = $this->findByMemberId($memberId);
        if (empty($theme)) {
            $theme = new Theme();
            $theme = $theme->loadDefaultValues();
            $theme->app_id = Yii::$app->id;
            $theme->member_id = $memberId;
            $theme->member_type = Yii::$app->user->identity->type;
        }

        $theme->layout = $layout;
        $theme->color = $color;
        $theme->save();
    }

    /**
     * @param $memberId
     * @param $memberType
     * @return array|\yii\db\ActiveRecord|null|Theme
     */
    public function findByMemberId($memberId)
    {
        return Theme::find()
            ->where(['member_id' => $memberId])
            ->one();
    }
}
