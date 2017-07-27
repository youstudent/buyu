<?php

namespace backend\controllers;

use common\models\Battery;
use common\models\GetGold;
use yii\web\Response;

class GetgoldController extends ObjectController
{
    public function actionIndex()
    {
       // GetGold::GetGold();
        $data = GetGold::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    
    /**
     * 领取金币修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
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
    
    
    /**
     * 同步救济金数据
     * @return array
     */
    public function actionGetgold(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = GetGold::GetGold();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
}
