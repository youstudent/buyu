<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/4
 * Time: 14:07
 */

namespace backend\controllers;


use common\models\Withdraw;
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
    
    
}