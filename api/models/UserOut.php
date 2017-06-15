<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */

namespace  api\models;

use common\models\GoldConfigObject;
use common\models\UserOutObject;

class UserOut extends UserOutObject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'game_id', 'time', 'gold'], 'integer'],
            [['nickname', 'game_class','gold_config'], 'string', 'max' => 32],
            [['game_id','game_class','notes','gold'],'required','on'=>'add'],
            [['game_id'],'validateGameId','on'=>'add']
        ];
    }

    /**
     * 判断游戏ID是否存在
     * @param $a
     * @param $p
     */
    public function validateGameId($a,$p)
    {
        if(!$this->hasErrors()){
            $model = Users::find()->where(['game_id'=>$this->game_id])->one();
            if(!$model){
                return $this->addError('game_id','游戏玩家不存在');
            }
        }
    }

    /**
     * 添加一条消费记录
     * @param $data
     * @return bool|void|
     */
    public function add($data)
    {
        $this->scenario = 'add';
        if($this->load($data,'') && $this->validate()){

            $this->gold_config = GoldConfigObject::getNameByCode($this->gold_config);
            $model = Users::find()->where(['game_id'=>$this->game_id])->one();
            $data = $model->consumeGold($this->gold_config,$this->gold);
            if($data){
                $this->user_id  = $model->id;
                $this->nickname = $model->nickname;
                $this->time     = time();
                return $this->save();
            }

            $message = $model->getFirstErrors();
            $message = reset($message);
            $this->addError('id',$message);
            return false;
        }
    }
}