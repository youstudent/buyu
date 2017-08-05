<?php

namespace backend\controllers;

class MonitoringController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
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

}
