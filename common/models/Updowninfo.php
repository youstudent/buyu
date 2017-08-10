<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "updowninfo".
 *
 * @property integer $id
 * @property integer $playerid
 * @property integer $familyid
 * @property integer $updown
 * @property integer $type
 * @property string $num
 * @property string $time
 */
class Updowninfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'updowninfo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
       return Yii::$app->commondb;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playerid', 'familyid', 'updown', 'type', 'num', 'time'], 'required'],
            [['playerid', 'familyid', 'updown', 'type', 'num', 'time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playerid' => '玩家ID',
            'familyid' => '族长ID',
            'updown' => '上下分',
            'type' => '类型',
            'num' => '数量',
            'time' => '时间',
        ];
    }
    
    /**
     *  和玩家建立一对一的关系
     */
    public function getUsers(){
        
        return $this->hasOne(Player::className(),['id'=>'playerid']);
    }
}
