<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use common\models\base\BaseModel;

/**
 * 伪删除行为
 *
 * Class SoftDeleteBehavior
 * @package common\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class SoftDeleteBehavior extends Behavior
{
    /**
     * @var string SoftDelete attribute
     */
    public $attribute = 'status';

    /**
     * @var callable|Expression The expression that will be used for generating the -1.
     * This can be either an anonymous function that returns the -1 value,
     * or an [[Expression]] object representing a DB expression (e.g. `-1`).
     * If not set, it will use the value of `-1` to set the attributes.
     */
    public $value;

    /**
     * @inheritdoc
     */
    // public function events()
    // {
    //     return [BaseActiveRecord::EVENT_BEFORE_DELETE => 'softDeleteEvent'];
    // }

    /**
     * Set the attribute with the current -1 to mark as deleted
     *
     * @param Event $event
     */
    public function softDeleteEvent($event)
    {
        // remove and mark as invalid to prevent real deletion
        $this->softDelete();
        $event->isValid = false;
    }

    /**
     * 伪删除
     */
    public function softDelete()
    {
        // set attribute with evaluated 1
        $attribute = $this->attribute;
        $this->owner->$attribute = $this->getValue(null);
        // save record
        return $this->owner->save(false, [$attribute]);
    }

    /**
     * 取消删除
     */
    public function unDelete()
    {
        // set attribute as 1
        $attribute = $this->attribute;
        $this->owner->$attribute = 1;
        // save record
        return $this->owner->save(false, [$attribute]);
    }

    /**
     * 直接删除
     *
     * Delete record from database regardless of the $safeMode attribute
     */
    public function hardDelete()
    {
        // store model so that we can detach the behavior
        /** @var BaseModel $model */
        $model = $this->owner;
        $this->detach();
        // delete as normal
        return 0 !== $model->delete();
    }

    /**
     * Evaluate the -1 to be saved.
     *
     * @param Event|null $event the event that triggers the current attribute updating.
     * @return mixed the attribute value
     */
    protected function getValue($event)
    {
        if ($this->value instanceof Expression) {
            return $this->value;
        } else {
            return $this->value !== null ? call_user_func($this->value, $event) : -1;
        }
    }
}
