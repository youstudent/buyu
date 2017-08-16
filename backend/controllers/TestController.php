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
    
    public function actionT()
    {
        // var_dump(\Yii::$app->redis->KEYS);
        $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        var_dump($redis->SMEMBERS ('onlinePlayer'));exit;
        // var_dump($redis->keys('*'));
        // $key = "test";
        // $value = "this is test";
        // $redis->set($key, $value);
        //$d = $redis->get($key);
        //var_dump($d);
        // $rdie = new \Redis();
        //var_dump($rdie->get('s1'));exit;
        // $v = Shop::find()->asArray()->all();
        //$id ='name';
        // $value = ['0'=>'琪琪',1=>'琪琪1'];
        // $new = serialize($v);
        $data = [['name' => '四川', 'age' => '20'], ['name' => '琪琪', 'age' => 29]];
        $datas = serialize($data);
        $redis->set('data', $datas);
        //  var_dump($redis->mset($data));
        //var_dump($redis->mget(['data']));
        // var_dump(\Yii::$app->redis->mset($data));
        // \Yii::$app->redis->expire('name1',5);
//        \Yii::$app->redis->set('user3','ccc');
        //\Yii::$app->redis->set('user4','ddd');
        //Redis->hmset($key, $value);
        // $re =  \Yii::$app->redis->hmset('name111',$new);
        // $re1 =  \Yii::$app->redis->get('data');//此时可以输出aaa
// ($re1) {
        // var_dump(unserialize($re1));
        // foreach (unserialize($re1) as $key=>$value){
        //var_dump($value['age']);
    
        // var_dump($re1);
    
        // var_dump($re,$re1);
        //\Yii::$app->redis->flushall();//删除redis中的所有数据
    
    }
    
    /**
     *  游戏服务器的用户表
     */
    public function actionGetUser(){
      $re =  Test::find()->all();
      var_dump($re);
    }
    
}