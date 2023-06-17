<?php

namespace addons\TinyBlog\frontend\widgets\nav;

use Yii;
use yii\base\Widget;
use common\enums\StatusEnum;

/**
 * Class NavWidget
 * @package addons\TinyBlog\frontend\widgets\nav
 * @author jianyan74 <751393839@qq.com>
 */
class NavWidget extends Widget
{
    /**
     * @return string|void
     */
    public function run()
    {
        $cateId = Yii::$app->request->get('cate_id');
        $singleId = Yii::$app->request->get('single_id');
        $tagId = Yii::$app->request->get('tag_id');

        $cate = Yii::$app->tinyBlogService->cate->findAll();
        foreach ($cate as &$item) {
            $item['select'] = StatusEnum::DISABLED;
            $cateId === $item['id'] && $item['select'] = StatusEnum::ENABLED;
        }

        $single = Yii::$app->tinyBlogService->single->findAll();
        foreach ($single as &$value) {
            $value['select'] = StatusEnum::DISABLED;
            $singleId === $value['id'] && $value['select'] = StatusEnum::ENABLED;
        }

        return $this->render('index', [
            'cate' => $cate,
            'single' => $single,
            'isIndex' => (empty($cateId) && empty($singleId) && empty($tagId)) ? StatusEnum::ENABLED : StatusEnum::DISABLED
        ]);
    }
}
