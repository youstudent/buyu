<?php

namespace backend\models;

use common\models\Player;
use Yii;

/**
 * This is the model class for table "blacklist".
 *
 * @property integer $id
 * @property integer $playerId
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blacklist';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->commondb;
      //  return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['playerId'], 'required'],
            [['playerId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playerId' => '玩家ID',
        ];
    }
    
    /**
     *  和名单表与用户表建立一对一的关系
     */
    public function getUsers(){
        return $this->hasOne(Player::className(),['id'=>'playerId']);
    }
}
