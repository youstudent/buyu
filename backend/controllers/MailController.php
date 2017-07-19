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
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Mail::findOne($id);
        $JSON = json_decode($model->number,true);
        $data  =[];
        $re = Mail::$give;
        
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
            
        }
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }

}
