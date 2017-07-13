<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:07
 */

namespace backend\controllers;


use common\models\Test;
use yii\web\Controller;

class TestController extends ObjectController
{
    public function actionTest(){
        $model= new Test();
        return $this->render('index',['model'=>$model]);
    }
    
}