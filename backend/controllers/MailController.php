<?php

namespace backend\controllers;

use backend\models\Email;
use common\helps\getgift;
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
        $model = new Email();
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
        $model = new Email();
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
    
    
    /**
     * @return string
     */
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Email::findOne($id);
        $data =  getgift::prize($model);
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
    
    /**
     * 查看邮件内容
     * @return string
     */
    public function actionContent(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Email::findOne($id);
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
        if ($data){
            $data->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'数据异常'];
       
    }

}
