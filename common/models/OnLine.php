<?php

namespace common\models;

use backend\models\Users;
use Yii;

/**
 * This is the model class for table "{{%on_line}}".
 *
 * @property string $id
 * @property integer $game_id
 * @property integer $room_id
 * @property integer $number_num
 */
class OnLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%on_line}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'room_id', 'number_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'room_id' => 'Room ID',
            'number_num' => 'Number Num',
        ];
    }
    
    /**
     *   在线用户和用户建立一对一的关系
     */
    public function getUsers(){
    
        return $this->hasOne(Users::className(),['game_id'=>'game_id']);
    
    }
}
