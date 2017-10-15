<?php

namespace backend\controllers;

use common\models\RedeemCode;
use common\models\RedeemRecord;
use m35\thecsv\theCsv;
use yii\console\Response;
use yii\web\Request;

class RedeemCodeController extends ObjectController
{
    public function actionIndex()
    {
        $model =new RedeemCode();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('index',$data);
    }
    
    
    //兑换码的添加
    /**
     *
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new RedeemCode();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('add',['model'=>$model]);
    }
    
    //添加一次
    /**
     *
     * @return array|string
     */
    public function actionAddOne()
    {
        $this->layout = false;
        $model = new RedeemCode();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($model->one(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('one',['model'=>$model]);
    }
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = RedeemCode::findOne($id);
        $JSON = json_decode($model->prize,true);
        $data  =[];
        $re = \common\helps\getgift::getGiftss();
        
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
        }
        
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
    
    //删除兑换码
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format =\yii\web\Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = RedeemCode::findOne($id);
        if($model)
        {
            if($model->delete()) {
                return ['code'=>1,'message'=>'删除成功'];
            }
            $messge = $model->getFirstErrors();
            $messge = reset($messge);
            return ['code'=>0,'message'=>$messge];
        }
        return ['code'=>0,'message'=>'删除的ID不存在'];
    }
    
    
    //导出兑换
    public function actionExport(){
        $data = RedeemCode::find()->select(['redeem_code','name'])->where(['in','status',[0,2]])->asArray()->all();
        theCsv::export([
                'data'=>$data,
                'header' => ['兑换码','名称'],
        ]);
        //return $this->redirect(['redeem-code/index']);
    }
    
    
    /**
     * 玩家兑换列表
     */
    public function actionRecord()
    {
        $model =new RedeemRecord();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('record',$data);
    }
    
    
    
    /**
     *  修改兑换码
     */
    public function actionEdit()
    {
        $this->layout = false;
        //RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = RedeemCode::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format =\yii\web\Response::FORMAT_JSON;
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $data = json_decode($model->prize);
        $datas=[];
        foreach ($data as $K=>$v){
          $datas[]=$K;
        }
        $model->give_type=$datas;
        return $this->render('edit',['model'=>$model,'data'=>$data]);
    }
    
}
