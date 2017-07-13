<?php

namespace backend\controllers;

use common\models\SignBoard;
use yii\web\Response;

class SignBoardController extends ObjectController
{
    //捕鱼任务首页
    public function actionIndex()
    {
        $model  =SignBoard ::find()->asArray()->all();
        return $this->render('index',['data'=>$model]);
    }
    
    
    /**
     * 捕鱼任务改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = SignBoard::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->manage_id    = \Yii::$app->session->get('manageId');
            $model->manage_name  = \Yii::$app->session->get('manageName');
            $model->updated_at=time();
            if($model->load(\Yii::$app->request->post()) && $model->save())
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
