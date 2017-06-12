<?php
/**
 * Created by PhpStorm.
 * User: lrdoubleInt
 * Date: 2017/4/16
 * Time: 18:22
 */
namespace backend\controllers;

class ApiController extends ObjectController
{

    public function actionIndex()
    {
        return $this->render('index');
    }


}