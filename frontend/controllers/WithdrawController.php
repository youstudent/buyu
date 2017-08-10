<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/4
 * Time: 14:07
 */

namespace frontend\controllers;


use common\models\Family;
use frontend\models\Person;
use frontend\models\Withdraw;
use yii\web\Response;

class WithdrawController extends ObjectController
{
    // 提现列表
    public function actionList()
    {
        $model = new Withdraw();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('list',$data);
    }
    
    //提现通过或者拒绝
    public function actionPass(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Withdraw();
        if ($model->pass(\Yii::$app->request->get('id'),\Yii::$app->request->get('status'))){
            return ['code'=>1,'message'=>\Yii::t('app','操作成功')];
        }
        $message = $model->getFirstErrors();
        $message = reset($message);
        return ['code'=>0,'message'=>$message];
    }
    
    
    /**
     *   代理商 申请提现
     */
    public function actionAdd(){
        $this->layout = false;
        $model = new Withdraw();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post())){
                return ['code'=>1,'message'=>'申请成功!等待平台审核'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        $data = Family::findOne(['id'=>\Yii::$app->session->get('familyId')]);
        if ($data){
            $model->phone=$data->phone;
            $model->bank_card=$data->bankcard;
            $model->bank_name=$data->realname;
            $model->bank_opening=$data->bank;
        }
        //var_dump(\Yii::$app->session->get('gameId'));EXIT;
        return $this->render('add',['model'=>$model]);
        
    }
    
    
    /**
     *  查询个人信息
     */
    public function actionGetPerson(){
        $data = Person::findOne(['game_id'=>\Yii::$app->session->get('agencyId')]);
        return $this->render('edit',['data'=>$data]);
    
    }
    
    /**
     *  修改个人信息
     *
     */
    public function actionEdit(){
        $this->layout = false;
        $data = Person::findOne(['game_id'=>\Yii::$app->session->get('agencyId')]);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($data->load(\Yii::$app->request->post()) && $data->save() ){
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $data->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('edit2',['model'=>$data]);
    }
    
    
}