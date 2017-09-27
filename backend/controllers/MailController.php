<?php

namespace backend\controllers;

use backend\models\Email;
use common\models\Mail;
use common\services\Request;
use yii\helpers\Json;
use yii\web\Response;

class MailController extends ObjectController
{
    /**
     * 显示用户列表
     * @return string
     */
    public function actionIndex()
    {
        $model = new Mail();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('index',$data);
    }
    
    
    /**
     * 添加新的通知
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Mail();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'发布成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('add',['model'=>$model]);
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Mail::findOne($id);
        $JSON = json_decode($model->number,true);
        $data  =[];
        $re = Mail::$give;
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$re[$v['toolId']]]=$v['toolNum'];
                    }
                }
            }
        
        }
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
    
    public function actionContent(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Mail::findOne($id);
        return $this->render('content',['model'=>$model]);
    }
    
    
    /**
     *  删除 邮件
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
         $data =  Email::findOne(['id'=>$id]);
        $model = Mail::findOne($data->id);
        if ($data){
            $data->delete();
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'数据不同步'];
       
    }

}
