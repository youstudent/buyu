<?php

namespace backend\controllers;

use backend\models\Batterylocker;
use backend\models\Batteryrate;
use common\helps\getgift;
use common\helps\players;
use common\models\Battery;
use common\models\GetGold;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class BatteryController extends ObjectController
{
    public function actionIndex()
    {
        //Battery::GetBattery();
        $model  = Batterylocker::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('power ASC')->asArray()->all();
        return $this->render('index',['data'=>$data,'pages'=>$pages]);
    }
    
    
    /**
     * 添加 炮台倍数
     * @return array|string
     */
    public function actionAdd()
    {
        players::actionPermission();
        $this->layout = false;
        $model = new Batterylocker();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        return $this->render('add', ['model' => $model]);
    }
    
    
    /**
     *  修改 炮台倍数
     * @return array|string
     */
    public function actionEdit()
    {
        players::actionPermission();
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Batterylocker::findOne($id);
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
        $row = getgift::getType($model,'send','toolId','toolNum');
        $model->gift=$row['type'];
        return $this->render('edit',['model'=>$model,'data'=>$row['data']]);
    }
    
    /**
     *  删除 炮台倍数
     * @return array
     */
    public function actionDel()
    {
        players::actionPermission();
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        /**
         * 请求游戏服务端   删除数据
         */
      $model = Batterylocker::findOne($id);
      if ($model){
          $model->delete();
          return ['code'=>1,'message'=>'删除成功'];
      }
      return ['code'=>0,'message'=>'删除失败'];
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model =Batterylocker::findOne($id);
        $data =  getgift::prize($model,'send','toolId','toolNum');
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
}
