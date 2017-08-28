<?php
/**
 * Created by PhpStorm.
 * User: lrdoubleInt
 * Date: 2017/4/16
 * Time: 17:29
 */
namespace backend\controllers;

use backend\models\Manage;
use yii\web\Response;

class ManageController extends ObjectController
{

    /**
     * 管理员列表首页
     * @return string
     */
    public function actionIndex()
    {
        $data = Manage::find()->all();
        return $this->render('index',['data'=>$data]);
    }

    /**
     * 删除操作
     * @return array
     */
    public function actionDel()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Manage::findOne(\Yii::$app->request->get('id'));
        if($model->delete()) {
            return ['code'=>1,'message'=>'删除成功'];
        }

        $message = $model->getFirstErrors();
        $message = reset($message);
        return ['code'=>0,'message'=>$message];
    }

    /**
     * 添加管理员操作
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Manage();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post())){
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('addManage',['model'=>$model]);
    }

    /**
     * 修改管理员资料
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Manage::findOne($id);
        $model->password = '';
        if(\Yii::$app->request->isPost)
        {
            $model->scenario = 'edit';
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->validate())
            {
                if($model->password != ''){
                    $model->password = $model->Password($model->password);
                }else{
                    $password = Manage::findOne($id);
                    $model->password = $password->password;
                }

                if($model->save()){
                    return ['code'=>1,'message'=>'修改成功'];
                }
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('editManage',['model'=>$model]);
    }



    /**
     * 当前登录管理员修改密码
     * @return array|string
     */
    public function actionEditPassword()
    {
        $this->layout = false;
        $model = Manage::findOne(\Yii::$app->session->get('manageId'));
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->editPassword(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('editPassword',['model'=>$model]);
    }
}