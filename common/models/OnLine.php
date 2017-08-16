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
    
    
    /**
     * @param $id
     * @return mixed
     * 获取玩家房间号
     */
    public static function getRoom($id){
        $redis = self::getReids();
        $data =$redis->HGETALL('playerRoom')[$id];
        return $data;
    }
    
    
    /**
     * @param $room
     * @return mixed
     * 获取玩家房间人数
     */
    public static function getRoomNmu($room){
        $redis = self::getReids();
        $data =$redis->HGETALL('roomPlayerNum')[$room];
        return $data;
    }
    
    
    /**
     * @return \Redis
     *  连接redis
     */
    public static function getReids(){
        $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        return $redis;
    }
}
