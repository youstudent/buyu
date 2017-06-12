<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\Manage;
use Yii;
use yii\web\Controller;

/**
 * 后台管理员用户类
 * Class UserController
 * @package backend\controllers
 */
class LoginController extends Controller
{

    /**
     * 用户登录操作
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = false;
        $manage = new Manage();
        if (Yii::$app->request->isPost)
        {
            if($manage->login(Yii::$app->request->post()))
            {
                return $this->redirect(['site/index']);
            }
        }
       return $this->render('login',['model'=>$manage]);
    }

    /**
     * 用户退出登录操作
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->session->removeAll();
        return $this->redirect(['login/login']);
    }
}