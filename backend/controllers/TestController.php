<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:07
 */

namespace backend\controllers;


use backend\models\Shop;
use Behat\Gherkin\Loader\YamlFileLoader;
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
    
    
    
    public function actionGet(){
        //$this->layout=false;
        $model = new Shop();
    
        return $this->render('get',['model'=>$model]);
    }
    
    public function actionT(){
       
      // $v = Shop::find()->asArray()->all();
        //$id ='name';
       // $value = ['0'=>'琪琪',1=>'琪琪1'];
      // $new = serialize($v);
       var_dump(\Yii::$app->redis->set('aaa','四川'));
       // \Yii::$app->redis->expire('name1',5);
//        \Yii::$app->redis->set('user3','ccc');
        //\Yii::$app->redis->set('user4','ddd');
        //Redis->hmset($key, $value);
      // $re =  \Yii::$app->redis->hmset('name111',$new);
    $re1 =  \Yii::$app->redis->get('aaa');//此时可以输出aaa
// ($re1) {
          var_dump($re1);
      // }else{
      
      // }
       

       // var_dump($re1);
     
       // var_dump($re,$re1);
       // Yii::$app->redis->flushall();//删除redis中的所有数据
    }
    
    
    /**
     *  游戏服务器的用户表
     */
    public function actionGetUser(){
      $re =  Test::find()->all();
      var_dump($re);
    }
    
}