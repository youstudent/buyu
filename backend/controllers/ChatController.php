<?php

namespace backend\controllers;

use common\models\Chat;
use yii\web\Response;

class ChatController extends ObjectController
{
    public function actionIndex()
    {
        
        if (\Yii::$app->request->get('show') == 1) {
            $data = Chat::find()->andWhere(["status" => 1])->asArray()->all();
        } elseif (\Yii::$app->request->get('show') == 2) {
            $data = Chat::find()->andWhere(["status" => 2])->asArray()->all();
        } else {
            $data = Chat::find()->asArray()->all();
        }
        
        return $this->render('index',['data'=>$data]);
    }
    
    
    
    /**
     * 添加新的通知
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Chat();
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
    
    /**
     * 通知修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Chat::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
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
    
    /**
     * 通知删除操作
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = Chat::findOne($id);
        if($model)
        {
            if($model->delete()) {
                return ['code'=>1,'message'=>'删除成功'];
            }
            $messge = $model->getFirstErrors();
            $messge = reset($messge);
            return ['code'=>0,'message'=>$messge];
        }
        return ['code'=>0,'message'=>'删除的ID不存在'];
    }
    
}
