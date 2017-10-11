<?php

namespace backend\controllers;

use backend\models\Fish;
use common\helps\players;
use common\models\Fishing;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class FishingController extends ObjectController
{
    /**
     * 初始化显示列表
     * @return string
     */
    public function actionIndex()
    {
        $data = new Fish();
        $model = $data::find();
        if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(["fishtype"=>1]);
        } elseif (\Yii::$app->request->get('show') == 2) {
            $model->andWhere(["fishtype"=>2]);
        }elseif (\Yii::$app->request->get('show') == 3) {
            $model->andWhere(["fishtype"=>3]);
        }elseif (\Yii::$app->request->get('show') == 4) {
            $model->andWhere(["fishtype"=>4]);
        }elseif (\Yii::$app->request->get('show') == 5) {
            $model->andWhere(["fishtype"=>5]);
        }
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        
        return $this->render('index',['data'=>$data,'pages'=>$pages]);
    }
    
    /**
     * 修改操作
     * @return array|string
     */
    public function actionEdit()
    {
        players::actionPermission();
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Fish::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $model->rate=$model->rate/100;
        $model->ariserate=$model->ariserate/100;
        return $this->render('edit',['model'=>$model]);
    }
    
    
    
    //同步数据
    public function actionGetfishing(){
        players::actionPermission();
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Fishing::GetFishing();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    

}
