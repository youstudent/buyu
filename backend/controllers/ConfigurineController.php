<?php

namespace backend\controllers;

use yii\web\Response;

class ConfigurineController extends  ObjectController
{
    //配置货币
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    //修改货币
    public function actionEdit(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = GetGold::findOne($id);
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

}
