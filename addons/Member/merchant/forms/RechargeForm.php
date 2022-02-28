<?php

namespace addons\Member\merchant\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\forms\CreditsLogForm;

/**
 * Class RechargeForm
 * @package addons\Member\merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class RechargeForm extends Model
{
    const TYPE_MONEY = 'Money'; // 余额
    const TYPE_INT = 'Int'; // 积分
    const TYPE_GROWTH = 'Growth'; // 成长值

    const CHANGE_INCR = 1;
    const CHANGE_DECR = 2;

    public $old_num;
    public $change = self::CHANGE_INCR;
    public $money;
    public $int;
    public $growth;
    public $remark;
    public $type;

    protected $sercive;

    /**
     * @var array
     */
    public static $changeExplain = [
        self::CHANGE_INCR => '增加',
        self::CHANGE_DECR => '减少',
    ];

    public function rules()
    {
        return [
            [['change'], 'integer'],
            [['money'], 'number', 'min' => 0.01, 'max' => 999999.99],
            [['int', 'growth'], 'integer', 'min' => 1, 'max' => 999999],
            [['remark', 'type'], 'string'],
            [['type'], 'verifyEmpty'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_num' => '当前',
            'change' => '变更',
            'money' => '数量',
            'int' => '数量',
            'growth' => '数量',
            'remark' => '备注',
        ];
    }

    public function verifyEmpty()
    {
        if ($this->type == self::TYPE_MONEY && !$this->money) {
            $this->addError('money', '数量不能为空');
        }

        if ($this->type == self::TYPE_INT && !$this->int) {
            $this->addError('int', '数量不能为空');
        }

        if ($this->type == self::TYPE_GROWTH && !$this->growth) {
            $this->addError('growth', '数量不能为空');
        }
    }

    /**
     * @param $member
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function save($member)
    {
        $action = 'decr' . $this->type;
        if ($this->change == self::CHANGE_INCR) {
            $action = 'incr' . $this->type;
        }

        $num = $this->money;
        if ($this->type == self::TYPE_INT) {
            $num = $this->int;
        }

        if ($this->type == self::TYPE_GROWTH) {
            $num = $this->growth;
        }

        // 写入当前会员
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->services->member->set($member);

            // 变动积分/余额
            Yii::$app->services->memberCreditsLog->$action(new CreditsLogForm([
                'member' => Yii::$app->services->member->get($member->id),
                'num' => $num,
                'group' => 'manager',
                'remark' => !empty($this->remark) ? $this->remark : '管理员操作',
            ]));

            $transaction->commit();
        } catch (NotFoundHttpException $e) {
            $transaction->rollBack();
            $this->addError('remark', $e->getMessage());
            return false;
        }

        return true;
    }
}
