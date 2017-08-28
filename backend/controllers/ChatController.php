<?php

namespace backend\controllers;

use common\models\Chat;
use common\services\Request;
use yii\helpers\Json;
use yii\web\Response;

class ChatController extends ObjectController
{
    public function actionIndex($show)
    {
        
        if (\Yii::$app->request->get('show') == 1) {
            $data = Chat::find()->andWhere(["status" => 1])->asArray()->all();
        } elseif (\Yii::$app->request->get('show') == 0) {
            $data = Chat::find()->andWhere(["status" => 0])->asArray()->all();
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
     * 删除 聊天
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = Chat::findOne($id);
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/control/deleteChat';
        $re = Request::request_post_raw($url,$datas);
        if ($re['code']==1){
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'删除失败'];
        /*if($model)
        {
            if($model->delete()) {
                return ['code'=>1,'message'=>'删除成功'];
            }
            $messge = $model->getFirstErrors();
            $messge = reset($messge);
            return ['code'=>0,'message'=>$messge];
        }
        return ['code'=>0,'message'=>'删除的ID不存在'];*/
    }
    
    //同步数据
    public function actionGetchat(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Chat::GetChat();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    
}
