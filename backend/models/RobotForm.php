<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;



use common\helps\players;
use common\services\Request;
use yii\base\Model;
use yii\helpers\Json;


class RobotForm extends Model
{
    public $num; // 数量
    public $name; // 玩家名字
    public $id;  //玩家ID
    public $rate;  // 机器人命中率
    public static $option=[263=>'张三'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','num','rate'],'required'],
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
    
    
    /**\
     * @param $data
     * @return bool|
     */
    public function editRate($data)
    {
        if ($this->load($data) && $this->validate()) {
            if ($this->rate <=0  || $this->num <=0 || $this->rate >100 || $this->num >3){
                return $this->addError('rate','命中率在1-100之间,数量在1-3之间');
            }
            /*{
                playerId: 1,
                kickOff: [2,3],
                robotRate: 6000,
                robotNum: 2
             }*/
            $data=[];
            $data['playerId']=$this->id;
            $data['kickOff']=$this->name;
            $data['robotRate']=$this->rate*100;
            $data['robotNum']=$this->num;
            $new_data = Json::encode($data);
            /**
             *  机指派机器人
             */
            $url = \Yii::$app->params['Api'].'/control/addRobot';
            $re = Request::request_post_raw($url,$new_data);
            if ($re['code']== 1){
                return true;
            }
            return $this->addError('id','修改数据失败');
        }
        
    }
}