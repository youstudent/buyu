<?php

namespace backend\controllers;

use common\models\Mail;
use yii\web\Response;

class MailController extends \yii\web\Controller
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
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('add',['model'=>$model]);
    }

}
