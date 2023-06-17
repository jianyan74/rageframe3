<?php

namespace addons\TinyBlog\frontend\widgets\sidebar;

use Yii;
use yii\base\Widget;

/**
 * Class SidebarWidget
 * @package addons\TinyBlog\frontend\widgets\nav
 * @author jianyan74 <751393839@qq.com>
 */
class SidebarWidget extends Widget
{
    /**
     * @return string|void
     */
    public function run()
    {
        return $this->render('index', [
            'tags' => Yii::$app->tinyBlogService->tag->findAll(),
            'newest' => Yii::$app->tinyBlogService->article->newest(),
            'hot' => Yii::$app->tinyBlogService->article->hot(),
            'friendlyLinks' => Yii::$app->tinyBlogService->friendlyLink->findAll(),
        ]);
    }
}
