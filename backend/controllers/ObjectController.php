<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace  backend\controllers;

use yii\web\Controller;

/**
 * Class ObjectController
 * @package backend\controllers
 */
class ObjectController extends Controller{

    /**
     * 检查是否有权限执行代码
     * @param \yii\base\Action $action
     * @return bool | string
     */
    public function beforeAction($action)
    {
         return empty(\Yii::$app->session->get('manageId')) ? $this->redirect(['login/login']): true;
    }

}