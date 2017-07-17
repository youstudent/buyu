<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:07
 */

namespace backend\controllers;


use backend\models\Shop;
use common\models\Test;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TestController extends ObjectController
{
    public static $data=[];
    public function actionTest(){
        $model= new Test();
        return $this->render('index',['model'=>$model]);
    }
    
    public function actionIndex(){
        //查询道具列表
        $data = Shop::find()->asArray()->all();
        //格式化 道具列表
        $data=ArrayHelper::map($data,'order_number','name');
        //定义道具
        $datas = ['gold'=>'金币','zhuanshi'=>'钻石','baoshi'=>'宝石'];
        $dd = ArrayHelper::merge($data,$datas);
        self::$data=$dd;
        var_dump(self::$data);
    }
    
}