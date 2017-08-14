<?php

namespace backend\controllers;

use common\models\Diamonds;
use common\services\Request;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class DiamondsController extends ObjectController
{
    /**
     *  钻石等级 列表
     * @return string
     */
    public function actionIndex()
    {
        $model = Diamonds::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('need_diamond ASC')->asArray()->all();
        return $this->render('index',['data'=>$data,'pages'=>$pages]);
    }
    
    /**
     *  同步 游戏服务 钻石等级数据
     */
    public function actionGetDiamonds(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Diamonds::GetDiamonds();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    
    
    /**
     * 添加 钻石等级
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Diamonds();
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
     *  修改 钻石等级
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Diamonds::findOne($id);
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
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = Diamonds::$give;
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
        $model->content=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data]);
    }
    
    /**
     *   删除 钻石等级
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = Diamonds::findOne($id);
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/gameserver/control/deleteExchangeGold';
        $re = Request::request_post_raw($url,$datas);
        if ($re['code']==1){
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'删除失败'];
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Diamonds::findOne($id);
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = Diamonds::$give;
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

}
