<?php

namespace common\models\common;

use common\traits\Tree;

/**
 * This is the model class for table "{{%common_provinces}}".
 *
 * @property int $id ID
 * @property string $title 栏目名
 * @property int $pid 父栏目
 * @property string|null $short_title 缩写
 * @property int|null $area_code 区域编码
 * @property int|null $zip_code 邮政编码
 * @property string|null $pinyin 拼音
 * @property string|null $lng 经度
 * @property string|null $lat 纬度
 * @property int $level 级别
 * @property string $tree
 * @property int|null $sort 排序
 */
class Provinces extends \yii\db\ActiveRecord
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_provinces}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tree', 'title'], 'required'],
            [['id', 'pid', 'area_code', 'zip_code', 'level', 'sort'], 'integer'],
            [['title', 'short_title'], 'string', 'max' => 50],
            [['pinyin'], 'string', 'max' => 100],
            [['lng', 'lat'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 200],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'pid' => '上级',
            'short_title' => '缩写',
            'area_code' => '区域编码',
            'zip_code' => '邮政编码',
            'pinyin' => '拼音',
            'lng' => '经度',
            'lat' => '纬度',
            'level' => '级别',
            'tree' => 'Tree',
            'sort' => '排序',
        ];
    }
}
