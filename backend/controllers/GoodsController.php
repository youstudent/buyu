<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/13
 * Time: 11:45
 */

namespace backend\controllers;


use backend\models\Goods;
use backend\models\Users;
use Codeception\Module\REST;
use common\models\UsersGoldObject;
use common\services\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;

class GoodsController extends ObjectController
{
    //商品兑换列表
   /* public function actionIndex(){
        $model = new Goods();
        $model = $model->getList(\Yii::$app->request->get());
        return $this->render('index',['data'=>$model]);
    }*/
    
    public function actionIndex()
    {
        $goods = new Goods();
        $goods->load(\Yii::$app->request->get());
        $goods->initTime();
        $model      = '';
        if($goods->keyword != ''){
            //$agencyInfo = Goods::find()->where($goods->searchWhere())->all();
            $model = Goods::find()->andWhere($goods->searchWhere());
            
        }else {
            
            $model = Goods::find();
        
        }
        $model->andWhere(['>=','created_at',strtotime($goods->starttime)])->andWhere(['<=','created_at',strtotime($goods->endtime)]);
        $pages      = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data       = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('index',['model'=>$goods,'data'=>$data,'pages'=>$pages]);
    }
    
    
    //处理商品 状态
    
    public function actionStatus()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Goods::findOne(\Yii::$app->request->get('id'));
        if(!$model){
            return ['code'=>0,'message'=>'账号不存在!'];
        }
        if($model->status == 2){
            return ['code'=>0,'message'=>'该数据已处理!'];
        }
           $model->status = 2;
        if($model->save()){
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
    }
    
    
    
    //反馈  通过
    public function actionYes(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Goods::findOne(['id'=>1]);
        if ($model->status==3 || $model->status==2){
            return ['code'=>0,'message'=>'数据已经处理过了!'];
        }
        $game_id = $model->game_id;
        $data = Users::findOne(['game_id'=>$game_id]);
        if ($data===null || $data===false){
            return ['code'=>0,'message'=>'没有该用户!'];
        }
        $uids = $data->id;
        $rows = UsersGoldObject::findOne(['users_id'=>$uids,'gold_config'=>'金币']);
        if ($rows->gold<$model->gold){
           return ['code'=>0,'message'=>'金币不足!'];
        }
        $gold=$model->gold;
        $rows->gold=$rows->gold-$gold;
        $rows->save(false);
        $model->status=2;
        $model->updated_at=time();
        if ($model->save()){
            $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=shopSuccessFail&cash =".$model->gold."&uid=".$model->game_id."&result=".true;
             if (Request::request_get($url)===false){
                 return ['code'=>0,'message'=>'游戏端返回失败'];
             }
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
    }
    
    
    //反馈  拒绝    通知游戏端
    public function actionNo(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Goods::findOne(['id'=>\Yii::$app->request->get('id')]);
        if ($model->status==3 || $model->status==2){
            return ['code'=>0,'message'=>'数据已经处理过了!'];
        }
        $game_id = $model->game_id;
        $data = Users::findOne(['game_id'=>$game_id]);
        if ($data===null || $data===false){
            return ['code'=>0,'message'=>'没有该用户!'];
        }
        $model->status=3;
        if ($model->save()){
            $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=shopSuccessFail&cash =".$model->gold."&uid=".$model->game_id."&result=".false;
            if (Request::request_get($url)===false){
                return ['code'=>0,'message'=>'游戏端返回失败!'];
            }
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
        
    }
    
}