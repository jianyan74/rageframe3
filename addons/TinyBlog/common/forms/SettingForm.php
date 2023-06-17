<?php

namespace addons\TinyBlog\common\forms;

use yii\base\Model;

/**
 * Class SettingForm
 * @package addons\TinyBlog\common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class SettingForm extends Model
{
    public $share_title;
    public $share_cover;
    public $share_desc;
    public $share_link;

    public $title = '我的博客';
    public $logo;
    public $web_copyright;
    public $web_site_icp;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['share_title', 'share_cover'], 'string', 'max' => 100],
            [['share_link', 'share_desc'], 'string', 'max' => 255],
            [['share_link'], 'url'],
            [['title', 'logo', 'web_copyright', 'web_site_icp'], 'required'],
            [['title', 'logo', 'web_copyright', 'web_site_icp'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'share_title' => '分享标题',
            'share_cover' => '分享封面',
            'share_desc' => '分享描述',
            'share_link' => '分享链接',
            'title' => '博客名称',
            'logo' => '博客Logo',
            'web_copyright' => '版权所有',
            'web_site_icp' => '备案号',
        ];
    }
}
