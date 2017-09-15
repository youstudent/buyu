<?php

namespace backend\controllers;

use backend\models\Config;
use yii\web\Response;

class ConfigController extends \yii\web\Controller
{
    
    /**
     * 基础数据配置
     * @return string
     */
    public function actionIndex()
    {
        $data = Config::find()->select(['maxrate','minrate','fishgoldrate','goldrate','id'])->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    public function actionNumIndex()
    {
        $data = Config::find()->asArray()->all();
        
        return $this->render('num-index',['data'=>$data]);
    }
    
    /**
     * 基础数据比例
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Config::findOne($id);
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
        $model->minrate =$model->minrate/100;
        $model->maxrate =$model->maxrate/100;
        return $this->render('edit',['model'=>$model]);
    }
    
    
    /**
     * 基础数据 数量陪
     * @return array|string
     */
    public function actionNumEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Config::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->numedit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('num-edit',['model'=>$model]);
    }

}
