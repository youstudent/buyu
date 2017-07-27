<?php

namespace backend\controllers;

use common\models\SignBoard;
use common\services\Request;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class SignBoardController extends ObjectController
{
    //捕鱼任务首页
    public function actionIndex()
    {
       // SignBoard::GetSign();
        $data = new SignBoard();
        $model = $data::find();
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
     * 捕鱼任务改操作操作
     * @return array|string
     */
   /* public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = SignBoard::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->manage_id    = \Yii::$app->session->get('manageId');
            $model->manage_name  = \Yii::$app->session->get('manageName');
            $model->updated_at=time();
            if($model->load(\Yii::$app->request->post()) && $model->save())
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('edit',['model'=>$model]);
    }*/
    
    
    /**
     * 获取 任务列表
     */
    public function actionGetSign(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = SignBoard::GetSign();
        if ($code == 1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    
    /**
     *  添加 任务
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new SignBoard();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('add',['model'=>$model]);
    }
    
    /**
     *  修改 任务
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = SignBoard::findOne($id);
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
        $JSON = json_decode($model->give_number,true);
        $data  =[];
        $re = SignBoard::$give;
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
        $a = trim($model->from_fishing, "[");
        $b = trim($a, "]");
        $c = explode(",", $b);
        $model->from_fishing=$c;
        $model->give_number=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data]);
    }
    
    /**
     *   删除  公告
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = SignBoard::findOne($id);
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/gameserver/control/deleteFishTask';
        $re = Request::request_post_raw($url,$datas);
        if ($re['code']==1){
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'删除失败'];
        /*   if($model)
           {
               if($model->delete()) {
                   return ['code'=>1,'message'=>'删除成功'];
               }
               $messge = $model->getFirstErrors();
               $messge = reset($messge);
               return ['code'=>0,'message'=>$messge];
           }
           return ['code'=>0,'message'=>'删除的ID不存在'];*/
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = SignBoard::findOne($id);
        $JSON = json_decode($model->give_number,true);
        $data  =[];
        $re = SignBoard::$give;
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
        $a = trim($model->from_fishing, "[");
        $b = trim($a, "]");
        $c = explode(",", $b);
        
       /* $D  = [];
        foreach (SignBoard::$fishing as $k=>$v){
             if (array_key_exists($k,$c)){
                $D[$k]=$v;
             }
        }
        SignBoard::$prize=$D;*/
        $model->from_fishing=$c;
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }

}
