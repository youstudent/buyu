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
            /*{
                playerId: 1,
                kickOff: [2,3],
                robotRate: 6000,
                robotNum: 2
             }*/
            $data=[];
            $data['playerId']=$this->id;
            $data['robotRate']=$this->rate;
            $data['robotNum']=$this->num;
            var_dump($data);exit;
            // $value = $this->player_rate * 100;
            return $this->addError('id','修改数据失败');
        }
        
    }
}