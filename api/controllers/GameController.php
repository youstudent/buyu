<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/13
 * Time: 19:39
 */

namespace api\controllers;


use backend\models\Game;

class GameController extends ObjectController
{
    //设置 机器人胜率
    public function actionWinning(){
       $data = Game::findOne(['type'=>3]);
       if ($data){
           return $this->returnAjax(1,'成功',$data);
       }
        return $this->returnAjax(0,'参数不正确');
       
    }
    
    
    //房费管理     type=1 匹配模式   2 房卡模式
    public function actionRoomrate(){
        
        $type = \Yii::$app->request->get('type');
        $data = Game::findOne(['type'=>$type]);
        if ($data){
            return $this->returnAjax(1,'成功',$data);
        }
        return $this->returnAjax(0,'参数不正确');
        
    }
    
    
    //分享金币设置
    public function actionShare(){
        $data = Game::findOne(['type'=>4]);
        if ($data){
            return $this->returnAjax(1,'成功',$data);
        }
        return $this->returnAjax(0,'参数不正确');
    
    }
    
}