<?php

namespace common\models\member;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use common\models\base\User;
use common\traits\Tree;
use common\models\rbac\AuthAssignment;
use common\helpers\RegularHelper;
use common\enums\StatusEnum;
use common\enums\MemberTypeEnum;
use common\helpers\HashidsHelper;
use common\helpers\StringHelper;
use common\models\api\AccessToken;
use common\traits\HasOneMerchant;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户ID
 * @property int|null $store_id 店铺ID
 * @property string $username 帐号
 * @property string $password_hash 密码
 * @property string $auth_key 授权令牌
 * @property string|null $password_reset_token 密码重置令牌
 * @property string|null $mobile_reset_token 手机号码重置令牌
 * @property int|null $type 1:会员;2:后台管理员;3:商家管理员
 * @property string|null $realname 真实姓名
 * @property string|null $nickname 昵称
 * @property string|null $head_portrait 头像
 * @property int|null $gender 性别[0:未知;1:男;2:女]
 * @property string|null $qq qq
 * @property string|null $email 邮箱
 * @property string|null $birthday 生日
 * @property int|null $province_id 省
 * @property int|null $city_id 城市
 * @property int|null $area_id 地区
 * @property string|null $address 默认地址
 * @property string|null $mobile 手机号码
 * @property string|null $tel_no 电话号码
 * @property string|null $bg_image 个人背景图
 * @property string|null $description 个人说明
 * @property int|null $visit_count 访问次数
 * @property int|null $last_time 最后一次登录时间
 * @property string|null $last_ip 最后一次登录ip
 * @property int|null $role 权限
 * @property int|null $current_level 当前级别
 * @property int|null $level_expiration_time 等级到期时间
 * @property int|null $level_buy_type 1:赠送;2:购买
 * @property int|null $pid 上级id
 * @property int|null $level 级别
 * @property string|null $tree 树
 * @property string|null $promoter_code 推广码
 * @property int|null $certification_type 认证类型
 * @property string|null $source 注册来源
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 修改时间
 */
class Member extends User
{
    use Tree, HasOneMerchant;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'merchant_id',
                    'store_id',
                    'type',
                    'gender',
                    'province_id',
                    'city_id',
                    'area_id',
                    'visit_count',
                    'last_time',
                    'role',
                    'current_level',
                    'level_expiration_time',
                    'level_buy_type',
                    'pid',
                    'level',
                    'certification_type',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [['birthday'], 'safe'],
            [['email'], 'email'],
            [['username', 'qq', 'mobile', 'tel_no'], 'string', 'max' => 20],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '不是一个有效的手机号码'],
            [['password_hash', 'password_reset_token', 'mobile_reset_token', 'head_portrait'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['realname', 'promoter_code', 'source'], 'string', 'max' => 50],
            [['nickname', 'email'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 140],
            [['address', 'bg_image'], 'string', 'max' => 200],
            [['last_ip'], 'string', 'max' => 40],
            [['tree'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'password_hash' => '密码',
            'auth_key' => '授权令牌',
            'password_reset_token' => '密码重置令牌',
            'mobile_reset_token' => '手机号码重置令牌',
            'type' => '管理员类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'head_portrait' => '头像',
            'gender' => '性别',
            'qq' => 'qq',
            'email' => '邮箱',
            'birthday' => '生日',
            'province_id' => '所在省',
            'city_id' => '所在市',
            'area_id' => '所在区',
            'address' => '所在详细地址',
            'mobile' => '手机号码',
            'tel_no' => '家庭号码',
            'bg_image' => '个人背景图',
            'description' => '个人说明',
            'visit_count' => '访问次数',
            'last_time' => '最后一次登录时间',
            'last_ip' => '最后一次登录ip',
            'role' => '权限',
            'current_level' => '当前级别',
            'level_expiration_time' => '等级到期时间',
            'level_buy_type' => '等级类型', // 1:赠送;2:购买
            'pid' => '上级id',
            'level' => '级别',
            'tree' => '树',
            'promoter_code' => '推广码',
            'certification_type' => '认证类型',
            'source' => '注册来源',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联授权角色
     *
     * @return ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * 关联账号
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['member_id' => 'id']);
    }

    /**
     * 关联级别
     */
    public function getMemberLevel()
    {
        return $this->hasOne(Level::class, ['level' => 'current_level'])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()]);
    }

    /**
     * 关联第三方绑定
     */
    public function getAuth()
    {
        return $this->hasMany(Auth::class, ['member_id' => 'id'])->where(['status' => StatusEnum::ENABLED]);
    }

    /**
     * 关联标签
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTag()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable(TagMap::tableName(), ['member_id' => 'id'])
            ->asArray();
    }

    /**
     * 统计
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStat()
    {
        return $this->hasOne(Stat::class, ['member_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws Exception
     */
    public function beforeSave($insert)
    {
        empty($this->store_id) && $this->store_id = 0;
        empty($this->type) && $this->type = MemberTypeEnum::MEMBER;
        if ($this->isNewRecord) {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        // 处理上下级关系
        $this->autoUpdateTree();

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $account = new Account();
            $account->member_id = $this->id;
            $account->member_type = $this->type;
            $account->merchant_id = $this->merchant_id;
            $account->store_id = $this->store_id;
            $account->save();
            $updateData = [];
            empty($this->promoter_code) && $updateData['promoter_code'] = HashidsHelper::encode($this->id);
            if (empty($this->nickname) && !empty($this->mobile)) {
                $nickname = StringHelper::random(5).'_'.substr($this->mobile, -4);
                $this->nickname = $nickname;
                $updateData['nickname'] = $nickname;
            }

            !empty($updateData) && Member::updateAll($updateData, ['id' => $this->id]);

            // 统计
            $account = new Stat();
            $account->member_id = $this->id;
            $account->merchant_id = $this->merchant_id;
            $account->store_id = $this->store_id;
            $account->save();
        }

        if ($this->status == StatusEnum::DISABLED) {
            AccessToken::updateAll(['status' => $this->status], ['member_id' => $this->id]);
            // 记录行为
            Yii::$app->services->actionLog->create('memberBlacklist', '拉入黑名单');
        }

        if ($this->status == StatusEnum::DELETE) {
            Account::updateAll(['status' => $this->status], ['member_id' => $this->id]);
            AccessToken::updateAll(['status' => $this->status], ['member_id' => $this->id]);
            // 记录行为
            Yii::$app->services->actionLog->create('memberDelete', '删除用户');
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
