<?php

namespace addons\TinyBlog\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\TinyBlog\services
 * @property ConfigService $config 默认配置
 * @property ArticleService $article 文章
 * @property SingleService $single 单页
 * @property CateService $cate 分类
 * @property AdvService $adv 幻灯片
 * @property TagService $tag 标签
 * @property FriendlyLinkService $friendlyLink 友情链接
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'config' => ConfigService::class,
        'article' => ArticleService::class,
        'cate' => CateService::class,
        'single' => SingleService::class,
        'adv' => AdvService::class,
        'tag' => TagService::class,
        'friendlyLink' => FriendlyLinkService::class,
    ];
}
