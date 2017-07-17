<?php

namespace backend\controllers;

use common\models\RedeemCode;
use common\models\RedeemRecord;
use m35\thecsv\theCsv;
use yii\console\Response;

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
        //RedeemCode::setShop();
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
        //$model->initTime();
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
        //RedeemCode::setShop();
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
       // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = RedeemCode::findOne($id);
        $JSON = json_decode($model->prize,true);
        $data  =[];
        $re = RedeemCode::$give;
        
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
            
            
        }
        //var_dump($JSON);
        /*foreach ($JSON as  $k=>$v){
            if ($k=='gold'){     //金币
                $model->gold=$v;
            }
            if ($k=='diamond'){
                $model->diamond=$v;   //钻石
            }
            if ($k=='fishGold'){
                $model->fishGold=$v;   //宝石鱼币
            }
            if ($k=='1'){
                $model->one=$v;   //神灯
            }
            if ($k=='2'){
                $model->tow=$v;  //锁定
            }
            if ($k=='3'){
                $model->three=$v; //冻结
            }
            if ($k=='4'){
                $model->four=$v;  //核弹
            }
            if ($k=='5'){
                $model->five=$v; //狂暴
            }
            if ($k=='6'){
                $model->six=$v;   //黑洞
            }
            $data[]=$k;
        }*/
       // $model->give_type=$data;
       // var_dump($model->give_type);
       // var_dump(RedeemCode::$give);
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
        //theCsv::export('g_redeem_code');
        
        /*theCsv::export([
            'table' => 'g_redeem_code',
            'fields' => ['redeem_code','name'],
            'header' => ['兑换码','名称'],
        ]);*/
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
            \Yii::$app->response->format = Response::FORMAT_JSON;
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
