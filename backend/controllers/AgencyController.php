<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\Agency;
use yii\web\Response;

/**
 * 代理的控制器
 * Class AgencyController
 * @package backend\controllers
 */
class AgencyController extends ObjectController
{
    /**
     * 首页初始化列表、带搜索功能的
     * @return string
     */
    public function actionIndex()
    {
        $model = new Agency();
        $data = $model->search(\Yii::$app->request->get());

        return $this->render('index',$data);
    }


    /**
     * 账号开始与封停
     * 1.开启与封停使用的是一个接口
     * 算法思路、
     *  查询出现在的状态
     *  现在的状态为1修改为封停状态
     *  为2修改为开启状态
     *  为4修改为开启状态
     * 1.开启 2.封停 4.申请为代理时拒了该用户
     * @return array
     */
    public function actionStatus()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Agency::findOne(\Yii::$app->request->get('id'));
        if(!$model)
            return ['code'=>0,'message'=>'账号不存在!'];
        if($model->status == 1){
            $model->status = 2;
        }elseif($model->status == 2){
            $model->status = 1;
        } elseif($model->status == 4){
            $model->status = 1;
        } else{
            return ['code'=>0,'message'=>'非法操作!'];
        }
        if($model->save())
            return ['code'=>1,'message'=>'账号操作成功!'];

        return ['code'=>0,'message'=>'账号操作失败!'];
    }

    /**
     * 账号审核与通过
     * @return array
     */
    public function actionAudit()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Agency::findOne(\Yii::$app->request->get('id'));
        if(!$model)
            return ['code'=>0,'message'=>'账号不存在!'];
        if(\Yii::$app->request->get('audit') == 'yes'){
            $model->status = 1;
        }elseif(\Yii::$app->request->get('audit') == 'no'){
            $model->status = 4;
        }else{
            return ['code'=>0,'message'=>'非法操作!'];
        }

        if($model->save()) {
            return ['code'=>1,'message'=>'账号操作成功!'];
        }

        return ['code'=>0,'message'=>'账号操作失败!'];
    }

    /**
     * 平台添加代理商操作
     * @return array|string
     */
    public function actionAddNew()
    {
        $this->layout = false;
        $model = new Agency();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>\Yii::t('app','agency_add_ret')];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('addNewModal',['model'=>$model]);
    }

    /**
     * 修应代理商操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Agency::findOne($id);
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
     * 用户充值操作
     * @return array|string
     */
    public function actionPay()
    {
        $this->layout = false;
        if(empty(\Yii::$app->request->get('id'))){
            $id =  \Yii::$app->request->post('id');
        }else{
            $id =  \Yii::$app->request->get('id');
        }

        $model = Agency::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->pay(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>"充值成功！"];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        $model->goldArr = $model->getGold();
        return $this->render('pay',['model'=>$model]);
    }

    /**
     * 用户扣除操作
     * @return array|string
     */
    public function actionDeduct()
    {
        $this->layout = false;
        if(empty(\Yii::$app->request->get('id'))){
            $id =  \Yii::$app->request->post('id');
        } else{
            $id =  \Yii::$app->request->get('id');
        }

        $model = Agency::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            /**
             * 设置返回为json格式
             */
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->deduct(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>"扣除成功"];
            }
            /**
             * 发送错误读取错误是什么、并返回给客户端
             */
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }

        $model->goldArr = $model->getGold();
        return $this->render('deduct',['model'=>$model]);
    }
}