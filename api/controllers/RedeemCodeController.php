<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/12
 * Time: 09:07
 */

namespace api\controllers;


use common\models\RedeemCode;
use common\models\RedeemRecord;

class RedeemCodeController extends ObjectController
{
    // 验证兑换码
    public function actionCheck(){
        
        if(\Yii::$app->request->isPost)
        {
            $model = new RedeemCode();
            if($data =$model->check(\Yii::$app->request->post())){
                return $this->returnAjax(1,'成功',$data);
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return $this->returnAjax(0,$message,[]);
        }
        return $this->returnAjax(0,'Please submit with POST');
        
    }
    
    //玩家兑换记录的添加
    public function actionRecord(){
        if (\Yii::$app->request->isPost){
            $model = new RedeemRecord();
            if($model->add(\Yii::$app->request->post())){
                return $this->returnAjax(1,'成功');
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return $this->returnAjax(0,$message,[]);
        }
        return $this->returnAjax(0,'Please submit with POST');
    }
    
    
}