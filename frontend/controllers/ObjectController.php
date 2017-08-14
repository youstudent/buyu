<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace frontend\controllers;

use backend\models\Agency;
use yii\web\Controller;

class ObjectController extends Controller
{
    /**
     * 检查是否有权限执行代码
     * @param \yii\base\Action $action
     * @return bool | string
     */
    public function beforeAction($action)
    {
        $data = Agency::findOne(['id'=>\Yii::$app->session->get('agencyId')]);
        if ($data){
            if ($data->status!==1){
                \Yii::$app->session->removeAll();
                return $this->redirect(['login/login']);
            }
          
        }else{
            \Yii::$app->session->removeAll();
            return $this->redirect(['login/login']);
        }
        if(!empty(\Yii::$app->session->get('agencyId')) && \Yii::$app->session->get('status') == 1)
        {
            return true;
        }else{
            return $this->redirect(['login/login']);
        }
    }
}