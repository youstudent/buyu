<?php

namespace backend\controllers;

use common\models\Family;
use common\models\Familyplayer;
use yii\web\Response;

class FamilyController extends \yii\web\Controller
{
    
    /**
     * 家族 列表
     * @return string
     */
    public function actionIndex()
    {
        $model = new Family();
        $data = $model->search(\Yii::$app->request->get());
    
        return $this->render('index',$data);
    }
    
    
    /**
     *
     *   添加家族 并加添玩家
     */
    public function actionAdd(){
        $this->layout = false;
        $model = new Family();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'创建家族成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('add',['model'=>$model]);
        
    }
    
    
    /**
     *  修改家族
     */
    public function actionEdit(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Family::findOne($id);
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
        return $this->render('edit',['model'=>$model]);
    }
    
    
    /**
     *  平台给代理充值
     * @return array|string
     */
    public function actionPay()
    {
        $this->layout = false;
        if(empty(\Yii::$app->request->get('id'))){
            $id =  \Yii::$app->request->post('id');
        }else{
            $id =  \Yii::$app->request->get('id');
        }
        
        $model = Family::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->pay(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>"充值成功！"];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        $model->gold=$model->users->gold;
        $model->diamond=$model->users->diamond;
        $model->fishGold=$model->users->fishGold;
        return $this->render('pay',['model'=>$model]);
    }
    
    
    /**
     *  平台给 扣除代理商
     * @return array|string
     */
    public function actionOut()
    {
        $this->layout = false;
        if(empty(\Yii::$app->request->get('id'))){
            $id =  \Yii::$app->request->post('id');
        }else{
            $id =  \Yii::$app->request->get('id');
        }
        
        $model = Family::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
          
            if($model->out(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>"扣除成功！"];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        $model->gold=$model->users->gold;
        $model->diamond=$model->users->diamond;
        $model->fishGold=$model->users->fishGold;
        return $this->render('out',['model'=>$model]);
    }
    
    /**
     *  家族信息
     */
    public function actionGetSon(){
        $model = new Familyplayer();
        $data = $model->son(\Yii::$app->request->get());
        return $this->render('son',$data);
        
    }

}
