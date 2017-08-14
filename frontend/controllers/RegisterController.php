<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace frontend\controllers;

use frontend\models\Agency;
use yii\web\Controller;

class RegisterController extends ObjectController
{

    public $layout = false;

    public function actionIndex()
    {
        $model = new Agency();
        if(\Yii::$app->request->isPost)
        {
            if($model->add(\Yii::$app->request->post()))
                return $this->redirect(['login/audit','id'=>3]);
        }
        return $this->render('index',['model'=>$model]);
    }


}