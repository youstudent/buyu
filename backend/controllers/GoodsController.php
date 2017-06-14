<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/13
 * Time: 11:45
 */

namespace backend\controllers;


use backend\models\Goods;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\data\Pagination;
use yii\web\Controller;

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
    
}