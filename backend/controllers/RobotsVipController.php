<?php

namespace backend\controllers;

use backend\models\Robot;
use yii\web\Response;

class RobotsVipController extends \yii\web\Controller
{
    /**
     * 机器人参数列表
     * @return string
     */
    public function actionIndex()
    {
        $data = Robot::find()->asArray()->one();
        return $this->render('index', ['value' => $data]);
    }
    
    
    /**
     * 修改 机器人参数
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Robot::findOne($id);
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
