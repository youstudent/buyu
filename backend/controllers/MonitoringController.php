<?php

namespace backend\controllers;

use backend\models\Users;
use common\models\OnLine;
use common\services\Request;
use spec\Prophecy\Exception\Prophecy\ObjectProphecyExceptionSpec;
use yii\web\Response;

class MonitoringController extends ObjectController
{
    public function actionIndex()
    {
        //$model = new Users();
        //$data = $model->getUsers(\Yii::$app->request->get());
        $model = OnLine::find()->all();
        return $this->render('index',['data'=>$model]);
    }
    
    
    /**
     *  监控数据
     */
    public function actionGet(){
        header("Content-Type: text/json;charset=utf-8");
        $re1 = rand(1,2000);
        $re2 = rand(1,2000);
        $re3 = rand(1,2000);
        $re4 = rand(1,2000);
        $re5 = rand(1,2000);
        
        $zuanshi1 = rand(1,2000);
        $zuanshi2 = rand(1,2000);
        $zuanshi3 = rand(1,2000);
        $zuanshi4= rand(1,2000);
        $zuanshi5 = rand(1,2000);
        $students = [
['id'=>1,'room'=>'1','num'=>'1','bei'=>'12',"name"=>"龙龙",'name_id'=>'3211','gold'=>$re1,'zuanshi'=>$zuanshi1],
['id'=>2,'room'=>'4','num'=>'3','bei'=>'10',"name"=>"勇勇",'name_id'=>'3212','gold'=>$re2,'zuanshi'=>$zuanshi2],
['id'=>3,'room'=>'12','num'=>'4','bei'=>'30',"name"=>"强强",'name_id'=>'344211','gold'=>$re3,'zuanshi'=>$zuanshi3],
['id'=>4,'room'=>'15','num'=>'2','bei'=>'12',"name"=>"张三",'name_id'=>'13112','gold'=>$re4,'zuanshi'=>$zuanshi4],
['id'=>5,'room'=>'28','num'=>'1','bei'=>'21',"name"=>"李四",'name_id'=>'421211','gold'=>$re5,'zuanshi'=>$zuanshi5],
        ];
        
        echo json_encode($students);
        
    }
    
    
    /**
     *  机器人指派
     */
     public function actionRobot($room_id,$game_id){
        $data = OnLine::find()->where(['room_id'=>$room_id])->all();
        $datas=[];
        foreach ($data as $k=>$v){
            $datas[$v->users->game_id]=$v->users->nickname;
        }
        return $this->render('robot');
        
     }
     
     
     
     /**
      *  监控中心掉线接口
      */
     public function actionLostConnection(){
         \Yii::$app->response->format = Response::FORMAT_JSON;
         $id = \Yii::$app->request->get('id');
         $datas['playerId']=$id;
         //掉线的接口
         $result = Request::request_post(\Yii::$app->params['Api'].'/gameserver/control/ban',$datas);
         if ($result['code'] == 1){
             return ['code'=>1,'message'=>\Yii::t('app','操作成功')];
         }
     
     
     }
    
    
    /**
     * 监控中心停封玩家
     */
     public function actionStop(){
         \Yii::$app->response->format = Response::FORMAT_JSON;
         $id = \Yii::$app->request->get('id');
         $datas['playerId']=$id;
         $datas['banTime']=86400*1000;//将时间戳转化成毫秒
         $result = Request::request_post(\Yii::$app->params['Api'].'/gameserver/control/ban',$datas);
         if ($result['code'] == 1){
             return ['code'=>1,'message'=>\Yii::t('app','操作成功')];
         }
     }

}
