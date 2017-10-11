<?php

namespace backend\controllers;

use backend\models\Chat;
use common\services\Request;
use yii\helpers\Json;
use yii\web\Response;

class ChatController extends ObjectController
{
    public function actionIndex($show)
    {
        
        if (\Yii::$app->request->get('show') == 1) {
            $data = Chat::find()->andWhere(["useable" => 1])->asArray()->all();
        } elseif (\Yii::$app->request->get('show') == 0) {
            $data = Chat::find()->andWhere(["useable" => 0])->asArray()->all();
        } else {
            $data = Chat::find()->asArray()->all();
        }
        return $this->render('index',['data'=>$data]);
    }
    
    /**
     * 添加 聊天设置
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Chat();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->save())
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
     * 修改  聊天内容
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
     * 删除 聊天
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
