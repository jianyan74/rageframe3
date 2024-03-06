<?php

/**
 * IDE 组件提示,无任何实际功能
 *
 * Class Yii
 */
class Yii
{
    /**
     * @var MyApplication
     */
    public static $app;
}

/**
 * Class MyApplication
 *
 * @property \yii\redis\Connection $redis
 * @property \yii\queue\cli\Queue $queue 队列
 * @property \services\Application $services 基础服务层
 * @property \common\components\ByteDance $byteDance 字节跳动
 * @property \common\components\Pay $pay 支付组件
 * @property \jianyan\easywechat\Wechat $wechat 微信
 * @property \Da\QrCode\Component\QrCodeComponent $qr 二维码
 * @property \common\components\BaseAddonModule $addons
 * @property \addons\RfDemo\services\Application $rfDemoService 功能案例
 * @property \addons\Merchants\services\Application $merchantsService 商家
 * @property \addons\TinyShop\services\Application $tinyShopService 微商城
 * @property \addons\TinyStore\services\Application $tinyStoreService 门店(社区团购)
 * @property \addons\TinyDistribute\services\Application $tinyDistributeService 微分销
 * @property \addons\TinyAgent\services\Application $tinyAgentService 代理
 * @property \addons\Wechat\services\Application $wechatService 微信公众号
 * @property \addons\WechatWork\services\Application $wechatWorkService 企业微信
 * @property \addons\WechatMini\services\Application $wechatMiniService 微信小程序
 * @property \addons\TinyDoc\services\Application $tinyDocService 文档
 * @property \addons\TinyChat\services\Application $tinyChatService 微客服
 * @property \addons\TinyBlog\services\Application $tinyBlogService 博客文章
 * @property \addons\TinySign\services\Application $tinySignService 微签到
 * @property \addons\TinyCircle\services\Application $tinyCircleService 种草社区
 * @property \addons\TinyErrand\services\Application $tinyErrandService 微配送
 * @property \addons\BigWheel\services\Application $bigWheelService 大转盘
 * @property \addons\ByteDanceThirdParty\services\Application $byteDanceThirdPartyService 字节跳动第三方平台
 * @property \addons\TikTokPlatform\services\Application $tikTokPlatformService 抖音开放平台
 * @property \addons\TikTokShop\services\Application $tikTokShopService 抖店
 * @property \addons\Authority\services\Application $authorityService 系统更新
 * @property \Detection\MobileDetect $mobileDetect
 *
 * @author jianyan74 <751393839@qq.com>
 */
class MyApplication
{

}
