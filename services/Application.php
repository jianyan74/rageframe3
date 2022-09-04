<?php

namespace services;

use common\components\Service;

/**
 * Class Application
 * @package services
 *
 * 会员
 * @property \services\member\MemberService $member 会员
 * @property \services\member\MemberTagService $memberTag 会员标签
 * @property \services\member\MemberTagMapService $memberTagMap 会员标签关联
 * @property \services\member\AuthService $memberAuth 会员第三方授权
 * @property \services\member\AccountService $memberAccount 会员账号
 * @property \services\member\CertificationService $memberCertification 实名认证
 * @property \services\member\LevelService $memberLevel 会员级别
 * @property \services\member\LevelConfigService $memberLevelConfig 会员级别配置
 * @property \services\member\AddressService $memberAddress 会员收货地址
 * @property \services\member\InvoiceService $memberInvoice 会员发票管理
 * @property \services\member\BankAccountService $memberBankAccount 会员银行提现账号
 * @property \services\member\CreditsLogService $memberCreditsLog 会员变动日志
 * @property \services\member\WithdrawDepositService $memberWithdrawDeposit 会员提现
 *
 * 商户
 * @property \services\merchant\MerchantService $merchant 商户
 * @property \services\merchant\ShopService $merchantShop 商户店铺
 *
 * Api
 * @property \services\api\AccessTokenService $apiAccessToken 接口
 *
 * 第三方公用
 * @property \services\extend\ConfigService $extendConfig 配置
 * @property \services\extend\PayService $extendPay 支付
 * @property \services\extend\UploadService $extendUpload 上传
 * @property \services\extend\SmsService $extendSms 短信
 * @property \services\extend\AppPushService $extendAppPush app 推送
 * @property \services\extend\GeTuiService $extendGeTui app 个推
 * @property \services\extend\JPushService $extendJPush app 极光推送
 * @property \services\extend\PrinterService $extendPrinter 小票打印
 * @property \services\extend\YiLianYunService $extendYiLianYun 易联云小票打印
 * @property \services\extend\FeiEYunService $extendFeiEYun 飞鹅云小票打印机
 * @property \services\extend\XpYunService $extendXpYun 芯烨云小票打印机
 * @property \services\extend\HiPrintService $extendHiPrint 本地打印机
 * @property \services\extend\MapService $extendMap 地图
 * @property \services\extend\OpenPlatformService $extendOpenPlatform 开放平台
 * @property \services\extend\LogisticsService $extendLogistics 物流查询
 * @property \services\extend\DetectionService $extendDetection 访问设备信息
 *
 * 公用
 * @property \services\common\ActionLogService $actionLog 行为日志
 * @property \services\common\AttachmentService $attachment 公用资源
 * @property \services\common\AttachmentCateService $attachmentCate 公用资源分类
 * @property \services\common\AddonsService $addons 插件
 * @property \services\common\AddonsConfigService $addonsConfig 插件配置
 * @property \services\common\ArchivesService $archives 认证信息
 * @property \services\common\ArchivesApplyService $archivesApply 认证申请
 * @property \services\common\BaseService $base 基础
 * @property \services\common\BankNumberService $bankNumber 提现银行卡信息
 * @property \services\common\ConfigService $config 配置
 * @property \services\common\ConfigCateService $configCate 配置分类
 * @property \services\common\DevPatternService $devPattern 开发模式
 * @property \services\common\ThemeService $theme 主题
 * @property \services\common\MailerService $mailer 邮件
 * @property \services\common\MenuService $menu 菜单
 * @property \services\common\MenuCateService $menuCate 菜单分类
 * @property \services\common\NotifyConfigService $notifyConfig 消息通知
 * @property \services\common\ProvincesService $provinces 省市区
 * @property \services\common\NotifyService $notify 消息
 * @property \services\common\NotifyMemberService $notifyMember 用户消息
 * @property \services\common\NotifyAnnounceService $notifyAnnounce 公告
 * @property \services\common\LogService $log 全局日志
 *
 * RBAC
 * @property \services\rbac\AuthService $rbacAuth 权限辅助
 * @property \services\rbac\AuthItemService $rbacAuthItem 权限
 * @property \services\rbac\AuthItemChildService $rbacAuthItemChild 被授权的权限
 * @property \services\rbac\AuthRoleService $rbacAuthRole 角色
 * @property \services\rbac\AuthAssignmentService $rbacAuthAssignment 授权关联
 *
 * oauth2
 * @property \services\oauth2\ServerService $oauth2Server oauth2服务
 * @property \services\oauth2\ClientService $oauth2Client oauth2客户端
 * @property \services\oauth2\AccessTokenService $oauth2AccessToken oauth2授权token
 * @property \services\oauth2\RefreshTokenService $oauth2RefreshToken oauth2刷新token
 * @property \services\oauth2\AuthorizationCodeService $oauth2AuthorizationCode oauth临时code
 *
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        /** ------ 会员 ------ **/
        'member' => 'services\member\MemberService',
        'memberTag' => 'services\member\MemberTagService',
        'memberTagMap' => 'services\member\MemberTagMapService',
        'memberAuth' => 'services\member\AuthService',
        'memberAccount' => 'services\member\AccountService',
        'memberAddress' => 'services\member\AddressService',
        'memberLevel' => 'services\member\LevelService',
        'memberLevelConfig' => 'services\member\LevelConfigService',
        'memberCertification' => 'services\member\CertificationService',
        'memberInvoice' => 'services\member\InvoiceService',
        'memberBankAccount' => 'services\member\BankAccountService',
        'memberCreditsLog' => 'services\member\CreditsLogService',
        'memberWithdrawDeposit' => 'services\member\WithdrawDepositService',
        /** ------ 商户 ------ **/
        'merchant' => 'services\merchant\MerchantService',
        'merchantShop' => 'services\merchant\ShopService',
        /** ------ api ------ **/
        'apiAccessToken' => [
            'class' => 'services\api\AccessTokenService',
            'cache' => false, // 启用缓存到缓存读取用户信息
            'timeout' => 720, // 缓存过期时间，单位秒
        ],
        /** ------ 公用部分 ------ **/
        'attachment' => 'services\common\AttachmentService',
        'attachmentCate' => 'services\common\AttachmentCateService',
        'archives' => 'services\common\ArchivesService',
        'archivesApply' => 'services\common\ArchivesApplyService',
        'base' => 'services\common\BaseService',
        'bankNumber' => 'services\common\BankNumberService',
        'config' => 'services\common\ConfigService',
        'configCate' => 'services\common\ConfigCateService',
        'menu' => 'services\common\MenuService',
        'menuCate' => 'services\common\MenuCateService',
        'addons' => 'services\common\AddonsService',
        'addonsConfig' => 'services\common\AddonsConfigService',
        'actionLog' => 'services\common\ActionLogService',
        'provinces' => 'services\common\ProvincesService',
        'notify' => 'services\common\NotifyService',
        'notifyMember' => 'services\common\NotifyMemberService',
        'notifyAnnounce' => 'services\common\NotifyAnnounceService',
        'notifyConfig' => 'services\common\NotifyConfigService',
        'devPattern' => 'services\common\DevPatternService',
        'theme' => 'services\common\ThemeService',
        'mailer' => [
            'class' => 'services\common\MailerService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        'log' => [
            'class' => 'services\common\LogService',
            'queueSwitch' => false, // 是否丢进队列
            'exceptCode' => [403] // 除了数组内的状态码不记录，其他按照配置记录
        ],
        /** ------ 扩展部分 ------ **/
        'extendPay' => 'services\extend\PayService',
        'extendUpload' => 'services\extend\UploadService',
        'extendMap' => 'services\extend\MapService',
        'extendOpenPlatform' => 'services\extend\OpenPlatformService',
        'extendLogistics' => 'services\extend\LogisticsService',
        'extendDetection' => 'services\extend\DetectionService',
        'extendConfig' => 'services\extend\ConfigService',
        // app 推送
        'extendAppPush' => [
            'class' => 'services\extend\AppPushService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        'extendJPush' => 'services\extend\JPushService',
        'extendGeTui' => 'services\extend\GeTuiService',
        // 小票打印机
        'extendPrinter' => [
            'class' => 'services\extend\PrinterService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        'extendYiLianYun' => 'services\extend\YiLianYunService',
        'extendFeiEYun' => 'services\extend\FeiEYunService',
        'extendXpYun' => 'services\extend\XpYunService',
        'extendHiPrint' => 'services\extend\HiPrintService',
        // 短信发送
        'extendSms' => [
            'class' => 'services\extend\SmsService',
            'queueSwitch' => false, // 是否丢进队列
        ],
        /** ------ RBAC ------ **/
        'rbacAuth' => 'services\rbac\AuthService',
        'rbacAuthItem' => 'services\rbac\AuthItemService',
        'rbacAuthItemChild' => 'services\rbac\AuthItemChildService',
        'rbacAuthRole' => 'services\rbac\AuthRoleService',
        'rbacAuthAssignment' => 'services\rbac\AuthAssignmentService',
        /** ------ oauth2 ------ **/
        'oauth2Server' => 'services\oauth2\ServerService',
        'oauth2Client' => 'services\oauth2\ClientService',
        'oauth2AccessToken' => 'services\oauth2\AccessTokenService',
        'oauth2RefreshToken' => 'services\oauth2\RefreshTokenService',
        'oauth2AuthorizationCode' => 'services\oauth2\AuthorizationCodeService',
    ];
}
