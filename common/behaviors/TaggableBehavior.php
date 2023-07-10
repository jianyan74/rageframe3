<?php

namespace common\behaviors;

use yii\db\Query;
use yii\db\ActiveRecord;
use yii\base\Behavior;

/**
 * 标签行为
 * TaggableBehavior
 *
 * @property ActiveRecord $owner
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class TaggableBehavior extends Behavior
{
    /**
     * 是否将标签返回为数组而不是字符串
     *
     * @var boolean whether to return tags as array instead of string
     */
    public $tagValuesAsArray = false;

    /**
     * 关联属性
     *
     * @var string the tags relation name
     */
    public $tagRelation = 'tags';

    /**
     * 关联中间表属性
     *
     * @var string
     */
    public $tagAssnRelation = 'tagMap';

    /**
     * 标签表 - 标签名称字段
     *
     * @var string the tags model value attribute name
     */
    public $tagValueAttribute = 'title';

    /**
     * 标签表 - 关联数量字段名
     *
     * @var string|false the tags model frequency attribute name
     */
    public $tagFrequencyAttribute = 'frequency';

    /**
     * @var string[]
     */
    private $_tagValues;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Returns tags.
     * @param boolean|null $asArray
     * @return string|string[]
     */
    public function getTagValues($asArray = null)
    {
        if (!$this->owner->getIsNewRecord() && $this->_tagValues === null) {
            $this->_tagValues = [];

            /* @var ActiveRecord $tag */
            foreach ($this->owner->{$this->tagRelation} as $tag) {
                $this->_tagValues[] = $tag->getAttribute($this->tagValueAttribute);
            }
        }

        if ($asArray === null) {
            $asArray = $this->tagValuesAsArray;
        }

        if ($asArray) {
            return $this->_tagValues === null ? [] : $this->_tagValues;
        } else {
            return $this->_tagValues === null ? '' : implode(', ', $this->_tagValues);
        }
    }

    /**
     * Sets tags.
     * @param string|string[] $values
     */
    public function setTagValues($values)
    {
        $this->_tagValues = $this->filterTagValues($values);
    }

    /**
     * Adds tags.
     * @param string|string[] $values
     */
    public function addTagValues($values)
    {
        $this->_tagValues = array_unique(array_merge($this->getTagValues(true), $this->filterTagValues($values)));
    }

    /**
     * Removes tags.
     * @param string|string[] $values
     */
    public function removeTagValues($values)
    {
        $this->_tagValues = array_diff($this->getTagValues(true), $this->filterTagValues($values));
    }

    /**
     * Removes all tags.
     */
    public function removeAllTagValues()
    {
        $this->_tagValues = [];
    }

    /**
     * Returns a value indicating whether tags exists.
     * @param string|string[] $values
     * @return boolean
     */
    public function hasTagValues($values)
    {
        $tagValues = $this->getTagValues(true);

        foreach ($this->filterTagValues($values) as $value) {
            if (!in_array($value, $tagValues)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function afterSave()
    {
        if ($this->_tagValues === null) {
            return;
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete();
        }

        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];
        /* @var ActiveRecord $class */
        $class = $tagRelation->modelClass;
        $rows = [];

        foreach ($this->_tagValues as $value) {
            /* @var ActiveRecord $tag */
            $tag = $class::findOne([$this->tagValueAttribute => $value]);

            if ($tag === null) {
                $tag = new $class();
                $tag->setAttribute($this->tagValueAttribute, $value);
            }

            if ($this->tagFrequencyAttribute !== false) {
                $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                $tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
            }

            if ($tag->save()) {
                $rows[] = [$this->owner->getPrimaryKey(), $tag->getPrimaryKey()];
            }
        }

        if (!empty($rows)) {
            $this->owner->getDb()
                ->createCommand()
                ->batchInsert($pivot, [key($tagRelation->via->link), current($tagRelation->link)], $rows)
                ->execute();
        }
    }

    /**
     * @return void
     */
    public function beforeDelete()
    {
        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];

        if ($this->tagFrequencyAttribute !== false) {
            /* @var ActiveRecord $class */
            $class = $tagRelation->modelClass;

            $pks = (new Query())
                ->select(current($tagRelation->link))
                ->from($pivot)
                ->where([key($tagRelation->via->link) => $this->owner->getPrimaryKey()])
                ->column($this->owner->getDb());

            if (!empty($pks)) {
                $class::updateAllCounters([$this->tagFrequencyAttribute => -1], ['in', $class::primaryKey(), $pks]);
            }
        }

        $this->owner->getDb()
            ->createCommand()
            ->delete($pivot, [key($tagRelation->via->link) => $this->owner->getPrimaryKey()])
            ->execute();
    }

    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values)
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(',', $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}
