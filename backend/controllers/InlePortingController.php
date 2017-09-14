<?php

namespace backend\controllers;

use common\models\InletPorting;
use yii\web\Response;

class InlePortingController extends ObjectController
{
    public function actionIndex()
    {
        $data = InletPorting::find()->asArray()->all();
        return $this->render('index',['data'=>$data]);
    }
    
    
    //设置 游戏入口
    public function actionStatus()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = InletPorting::findOne(\Yii::$app->request->get('id'));
        if(!$model){
            return ['code'=>0,'message'=>'账号不存在!'];
        }
        $model->manage_id    = \Yii::$app->session->get('manageId');
        $model->manage_name  = \Yii::$app->session->get('manageName');
        $model->updated_at  = time();
        $model->status=\Yii::$app->request->get('status');
        if($model->save()){
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
    }
    
    
    public function actionYes(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = InletPorting::findOne(\Yii::$app->request->get('id'));
        if(!$model){
            return ['code'=>0,'message'=>'账号不存在!'];
        }
        $model->manage_id    = \Yii::$app->session->get('manageId');
        $model->manage_name  = \Yii::$app->session->get('manageName');
        $model->updated_at  = time();
        $model->type=\Yii::$app->request->get('type');
        if($model->save()){
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
        return ['code'=>0,'message'=>'账号操作失败!'];
    }
    
    

}
