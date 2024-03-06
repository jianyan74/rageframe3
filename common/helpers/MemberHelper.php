<?php

namespace common\helpers;

use common\enums\MemberTypeEnum;

/**
 * Class MemberHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberHelper
{
    const ONE = 'one';
    const TWO = 'two';

    /**
     * @param $member
     * @return string
     */
    public static function html($member, $style = self::ONE)
    {
        if (empty($member)) {
            return '';
        }

        $url = Url::toRoute(['/member/member/view', 'id' => $member->id]);
        switch ($member['type']) {
            case MemberTypeEnum::MEMBER :
                $name = '昵称: '.Html::encode($member['nickname']);
                $hideName = Html::encode(StringHelper::textNewLine($member->nickname, 6, 1)[0]);
                break;
            default;
                $name = '账号: '.Html::encode($member['username']);
                $hideName = Html::encode(StringHelper::textNewLine($member->username, 6, 1)[0]);
                break;
        }

        switch ($style) {
            default :
                $head_portrait = Html::img(ImageHelper::defaultHeaderPortrait($member->head_portrait), [
                    'class' => 'img-circle elevation-1',
                    'width' => '35',
                    'height' => '35',
                ]);

                $toggle = [];
                $toggle[] = 'ID: '.$member->id;
                $toggle[] = $name;
                $toggle[] = '手机: '.(!empty($member['mobile']) ? StringHelper::hideStr($member['mobile'], 3, 4) : '-');
                $toggle = "<div class='text-left'>".implode('<br>', $toggle)."</div>";

                return '<div class="text-center openIframeView" href="'.$url.'">'.$head_portrait.'<a class="users-list-name pt-1" data-toggle="tooltip" data-placement="bottom" data-html="true" title="'.$toggle.'" href="javascript: void(0)">'.$hideName.'</a></div>';
            case self::TWO :
                $array = [];
                $array[] = 'ID: '.$member['id'];
                $array[] = $name;
                $array[] = '手机: '.(!empty($member['mobile']) ? StringHelper::hideStr($member['mobile'], 3, 4) : '-');

                return implode('<br>', $array);
        }
    }

    /**
     * @param $searchModel
     * @param $model
     * @return array
     */
    public static function gridView(
        $searchModel,
        $label = '用户',
        $attribute = 'member_id',
        $relevancy = 'member',
        $default = '游客',
        $style = 'one'
    ) {
        return [
            'label' => $label,
            'attribute' => $attribute,
            'headerOptions' => ['class' => 'col-md-1 text-align-center'],
            'contentOptions' => ['class' => 'text-align-center'],
            'filter' => Html::activeTextInput($searchModel, $attribute, [
                    'class' => 'form-control',
                    'placeholder' => '用户 ID',
                ]
            ),
            'value' => function ($model) use ($relevancy, $default, $style) {
                if (empty($model->$relevancy)) {
                    return $default;
                }

                return self::html($model->$relevancy, $style);
            },
            'format' => 'raw',
        ];
    }
}
