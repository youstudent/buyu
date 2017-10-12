<?php

namespace backend\controllers;

use backend\models\Messageboard;
use yii\data\Pagination;
use yii\web\Response;

class MessageboardController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Messageboard();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('index',$data);
    }
    
    
    /**
     *   删除留言板数据
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $re =  Messageboard::findOne(['id'=>$id]);
        if ($re){
        $re->delete();
        return ['code' => 1, 'message' => '删除成功'];
        }
        return ['code' => 0, 'message' => '数据异常'];
    }

}
