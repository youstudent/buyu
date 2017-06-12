<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $id
 * @property integer $game_id
 * @property string $nickname
 * @property string $autograph
 * @property string $phone
 * @property string $gold
 * @property string $gold_all
 * @property string $reg_time
 * @property string $game_count
 * @property string $head
 * @property integer $status
 *
 * @property GameExploitsObject[] $gameExploits
 * @property UserOutObject[] $userOuts
 * @property UserPayObject[] $userPays
 */
class UsersObject extends Object
{
    /**
     * 这个用户的所有金币
     * @var array
     */
    public $goldArr = [];


    /**
     * 金币的配置
     * @var string
     */
    public static $goldConfig = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'gold', 'gold_all', 'reg_time', 'game_count', 'status'], 'integer'],
            [['nickname'], 'string', 'max' => 32],
            [['autograph', 'head'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => '玩家ID',
            'nickname' => '玩家昵称',
            'autograph' => '个性签名',
            'phone' => '手机号码',
            'gold' => '金币',
            'gold_all' => '总计消费金币',
            'reg_time' => '注册时间',
            'game_count' => '游戏总局数',
            'head' => '头像',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGameExploits()
    {
        return $this->hasMany(GameExploitsObject::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOuts()
    {
        return $this->hasMany(UserOutObject::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPays()
    {
        return $this->hasMany(UserPayObject::className(), ['user_id' => 'id']);
    }


    /**
     * 获取用户的所有金币余额
     * @return array
     */
    public function getGold()
    {

        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        $data = [];
        foreach (self::$goldConfig as $key=>$value)
        {
            $data[$value['name']] = $this->getNoeGold($value['name']);
        }

        return $data;
    }

    /**
     * 获取一种货币的余额
     * @param string $name
     * @return mixed
     */
    public function getNoeGold($name = '')
    {
        $data = UsersGoldObject::find()
                ->andWhere(['users_id'=>$this->id])
                ->andWhere(['gold_config'=>$name])
                ->select(['gold'])
                ->asArray()
                ->one();
        return $data['gold'];
    }


    /**
     * 执行充值操作
     * @param $payGoldConfig
     * @param $payGold
     * @return bool
     */
    public function payGold($payGoldConfig,$payGold)
    {
        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        /**
         * 循环处理代码、避免数据库压力
         */
        foreach (self::$goldConfig as $key=>$value)
        {

            if($value['name'] == $payGoldConfig)
            {
                /*
                 * 1代表数量 2代表时间处理
                 *  */
                if($value['type'] == 1)
                {
                    $data = UsersGoldObject::find()
                            ->andWhere(['users_id'=>$this->id])
                            ->andWhere(['gold_config'=>$payGoldConfig])
                            ->one();
                    $data->gold = ($data->gold + $payGold);
                    $data->sum_gold   = ($data->sum_gold + $payGold);

                    return $data->save();

                }
                elseif ($value['type'] == 2)
                {
                    $data = UsersGoldObject::find()
                            ->andWhere(['users_id'=>$this->id])
                            ->andWhere(['gold_config'=>$payGoldConfig])
                            ->one();

                    $data->gold = strtotime('+1month',$data->value);
                    //时间未执行使用多少 作者放得BUG

                    return $data->save();
                }
            }
        }
    }

    /**
     * 执行消费操作
     * @param $payGoldConfig
     * @param $payGold
     * @return bool
     */
    public function consumeGold($goldConfig,$payGold)
    {

        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        /**
         * 循环处理代码、避免数据库压力
         */
        foreach (self::$goldConfig as $key=>$value)
        {
            if($value['name'] == $goldConfig)
            {
                if($value['type'] == 1)
                {
                    $data = UsersGoldObject::find()
                        ->andWhere(['users_id'=>$this->id])
                        ->andWhere(['gold_config'=>$goldConfig])
                        ->one();
                    if($data['gold'] < $payGold){
                        return $this->addError('game_id','余额不足');
                    }
                    $data->gold       = ($data->gold - $payGold);
                    return $data->save();
                }elseif ($value['type'] == 2){
                    return true;
                }
            }
        }
    }
}
