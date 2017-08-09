<?php

namespace backend\controllers;

use common\models\Robot;
use common\services\Request;
use yii\helpers\Json;
use yii\web\Response;

class RobotController extends \yii\web\Controller
{
    
    /**
     *  机器人列表
     * @return string
     */
    public function actionIndex()
    {
       // Robot::GetVip();
        $model = new Robot();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('index',$data);
    }
    
    
    /**
     *   添加机器人
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Robot();
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
        /**
         *  初始化数据
         */
        $model->gold=2000;
        $model->fish_gold=2000;
        $model->diamond=2000;
        $model->robot_win_rate=20;
        return $this->render('add',['model'=>$model]);
    }
    
    
    /**
     *  修改 机器人
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Robot::findOne($id);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        
        }
         return $this->render('edit',['model'=>$model]);
    
    }
    
    
    /**
     *   删除 机器人
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $model = Robot::findOne($id);
        $data = [];
        $data['id'] = $model->id;
        $datas = Json::encode($data);
        /**
         *  操作 发送请求到服务器
         */
        $url = \Yii::$app->params['Api'] . '/gameserver/control/deleteNotice';
        $re = Request::request_post_raw($url, $datas);
        if ($re['code'] == 1) {
            $model->delete();
            return ['code' => 1, 'message' => '删除成功'];
        }
        return ['code' => 0, 'message' => '删除失败'];
    }
}
