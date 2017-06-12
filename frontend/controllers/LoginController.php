<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace frontend\controllers;

use frontend\models\Agency;
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
        $manage = new Agency();
        if (Yii::$app->request->isPost)
        {
            $loginStatus = $manage->login(Yii::$app->request->post());
            if($loginStatus == 1)
            {
                return $this->redirect(['site/index']);
            }elseif ($loginStatus == 2) {
                return $this->redirect(['login/audit','id'=>'2']);
            }elseif ($loginStatus == 3){
                return $this->redirect(['login/audit','id'=>'3']);
            }elseif ($loginStatus == 4){
                return $this->redirect(['login/audit','id'=>'4']);
            }
        }
       return $this->render('login',['model'=>$manage]);
    }

    public function actionAudit()
    {
        $this->layout =  false;
        return $this->render('audit');
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