<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;



use common\helps\players;
use yii\base\Model;


class RateForm extends Model
{
    public $player_rate; //玩家命中率
    public $room_rate;   //房间命中率
    public $id;  //玩家ID
    public $vip_rate;  // vip命中率
    public $battery_rate;  //炮的命中率
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_rate','room_rate'],'required'],
            [['player_rate','room_rate','id'],'number']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'player_rate' => '玩家命中率%',
            'room_rate' => '房间命中率%',
            'id' => '玩家ID',
            'vip_rate' => 'vip命中率%',
            'battery_rate' => '炮的命中率%',
        ];
    }
    
    
    public function editRate($data)
    {
        if ($this->load($data) && $this->validate()) {
            if ($this->player_rate <0  || $this->room_rate <0 || $this->player_rate >100 || $this->room_rate >100){
                return $this->addError('player_rate','命中率在0-100之间');
            }
            //修改玩家和房间的命中率
            $redis = players::getReids();
            $playerRate = $redis->hGetAll('playerRate');
            //修改玩家的命中率
            foreach ($playerRate as $k => &$value) {
                if ($k == $this->id) {
                    $value = $this->player_rate * 100;
                }
            }
            //修改玩家的命中率
           // $redis->hMset('playerRate', $playerRate);
            
            //修改玩家房间的命中率
            //1. 根据玩家ID 查询玩家在那个房间,
            $room = players::getRoom($this->id);
            //2. 查询所有的房间,修改指定房间的命中率
            $roomRate = $redis->hGetAll('roomRate');
            //修改玩家所在房间的命中率
            foreach ($roomRate as $k => &$value) {
                if ($k == $room) {
                    $value = $this->room_rate * 100;
                }
            }
            if ($redis->hMset('playerRate', $playerRate) && $redis->hMset('roomRate', $roomRate)){
                return true;
            }
            //$redis->hMset('roomRate', $roomRate);
            return $this->addError('id','修改数据失败');
        }
        
    }
}