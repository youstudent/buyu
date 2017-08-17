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


class RobotForm extends Model
{
    public $num; // 数量
    public $name; // 玩家名字
    public $id;  //玩家ID
    public $rate;  // 机器人命中率
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num','rate'],'required'],
            [['num','rate','id'],'number']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'num' => '机器人数量',
            'rate' => '命中率%',
            'id' => '玩家ID',
            'name'=>'用户名'
        ];
    }
    
    
    public function editRate($data)
    {
        if ($this->load($data) && $this->validate()) {
            if ($this->rate <0  || $this->num <0 || $this->rate >100 || $this->num >3){
                return $this->addError('rate','命中率在0-100之间,数量在1-3之间');
            }
            // 当前玩家 加入多少机器人  并设置命中率
            var_dump($data);exit;
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