<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "familyrecord".
 *
 * @property integer $id
 * @property integer $familyid
 * @property integer $playerid
 * @property string $time
 * @property integer $type
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 */
class Familyrecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'familyrecord';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->commondb; //选择数据库连接对象
       // return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['familyid', 'playerid', 'type', 'gold', 'diamond', 'fishgold'], 'required'],
            [['familyid', 'playerid', 'type', 'gold', 'diamond', 'fishgold'], 'integer'],
            [['time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'familyid' => '家族ID',
            'playerid' => '玩家ID',
            'time' => '时间',
            'type' => '类型',
            'gold' => '金币',
            'diamond' => '钻石',
            'fishgold' => '鱼币',
        ];
    }
    
    /**
     *  动态记录和玩家建立多对一的关系
     */
    public function getUsers(){
        return $this->hasOne(Player::className(),['id'=>'playerid']);
    }
    
    
    /**
     *  查询玩家申请时间
     */
    public static function getApply_time($playerid,$type){
        $data = Familyrecord::find()->select('time')->where(['type'=>$type,'playerid'=>$playerid])->orderBy('time DESC')->asArray()->one();
        if ($data){
            return $data['time'];
        }
    }
    
    /**
     *  统计玩家总上分,金币,钻石
     *
     */
    public static function GameGold($playerid,$type){
        $row = self::find()->select(['sum(gold)'])->andWhere(['type'=>$type,'playerid'=>$playerid])->asArray()->one();
        return $row['sum(gold)']?$row['sum(gold)']:0;
    }
    
    /**
     * 统计玩家下分, 金币钻石
     */
    public static function GameDiamond($playerid,$type){
        $row = self::find()->select(['sum(diamond)'])->andWhere(['type'=>$type,'playerid'=>$playerid])->asArray()->one();
        return $row['sum(diamond)']?$row['sum(diamond)']:0;
        
    }
}
