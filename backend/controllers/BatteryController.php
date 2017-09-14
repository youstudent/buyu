<?php

namespace backend\controllers;

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
        $model  = Battery::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('multiple ASC')->asArray()->all();
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
        $model = new Battery();
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
        $model = Battery::findOne($id);
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
        $JSON = json_decode($model->give_gold_num,true);
        $data  =[];
        $re = Battery::$give;
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$key]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$v['toolId']]=$v['toolNum'];
                    }
                }
            }
            
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $model->type=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data]);
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
      $model = Battery::findOne($id);
      $url = \Yii::$app->params['Api'].'/control/deletebatterypower';
      $data=[];
      $data['id']=$id;
      $datas = Json::encode($data);
      $re = \common\services\Request::request_post_raw($url,$datas);
      if ($re['code']== 1){
          $model->delete();
          return ['code'=>1,'message'=>'删除成功'];
      }
      return ['code'=>0,'message'=>'删除失败'];
      /*$model = Battery::findOne($id);
      if ($model) {
          if ($model->delete()){
              return ['code' => 1, 'message' => '删除成功'];
          }
          $messge = $model->getFirstErrors();
          $messge = reset($messge);
          return ['code' => 0, 'message' => $messge];
      }*/
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Battery::findOne($id);
        $JSON = json_decode($model->give_gold_num,true);
        $data  =[];
        $re = Battery::$give;
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$re[$v['toolId']]]=$v['toolNum'];
                    }
                }
            }
            
        }
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
    /**
     * 同步炮台数据
     */
    public function actionGetbattery(){
        players::actionPermission();
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Battery::GetBattery();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
}
