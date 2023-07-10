<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * TaggableQueryBehavior
 *
 * @property \yii\db\ActiveQuery $owner
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class TaggableQueryBehavior extends Behavior
{
    /**
     * 通过标签ID查询内容
     *
     * Gets entities by any tags.
     * @param int|int[] $values
     * @return \yii\db\ActiveQuery the owner
     */
    public function anyTagIds($tagIds)
    {
        $model = new $this->owner->modelClass();
        $tagIds = $model->filterTagValues($tagIds);
        $tagRelation = $model->getRelation($model->tagRelation);
        // 中间表关联标签字段
        $assnRelation = $tagRelation->link['id'];
        $this->owner
            ->joinWith([$model->tagAssnRelation => function(ActiveQuery $query) use ($assnRelation, $tagIds) {
                return $query->andWhere([$assnRelation => $tagIds]);
            }]);

        return $this->owner;
    }

    /**
     * 通过标签查询内容
     *
     * Gets entities by any tags.
     * @param string|string[] $values
     * @param string|null $attribute
     * @return \yii\db\ActiveQuery the owner
     */
    public function anyTagValues($values, $attribute = null)
    {
        $model = new $this->owner->modelClass();
        $tagClass = $model->getRelation($model->tagRelation)->modelClass;

        $this->owner
            ->innerJoinWith($model->tagRelation, false)
            ->andWhere([$tagClass::tableName() . '.' . ($attribute ?: $model->tagValueAttribute) => $model->filterTagValues($values)])
            ->addGroupBy(array_map(function ($pk) use ($model) { return $model->tableName() . '.' . $pk; }, $model->primaryKey()));

        return $this->owner;
    }

    /**
     * Gets entities by all tags.
     * @param string|string[] $values
     * @param string|null $attribute
     * @return \yii\db\ActiveQuery the owner
     */
    public function allTagValues($values, $attribute = null)
    {
        $model = new $this->owner->modelClass();

        return $this->anyTagValues($values, $attribute)->andHaving(new Expression('COUNT(*) = ' . count($model->filterTagValues($values))));
    }

    /**
     * Gets entities related by tags.
     * @param string|string[] $values
     * @param string|null $attribute
     * @return \yii\db\ActiveQuery the owner
     */
    public function relatedByTagValues($values, $attribute = null)
    {
        return $this->anyTagValues($values, $attribute)->addOrderBy(new Expression('COUNT(*) DESC'));
    }
}
