<?php

namespace backend\controllers;

use common\models\Day;
use common\services\Request;
use Symfony\Component\DomCrawler\Tests\Field\InputFormFieldTest;
use yii\helpers\Json;
use yii\web\Response;

class DayController extends ObjectController
{
    //每日签到首页
    public function actionIndex()
    {
        $model  = Day::find()->where(['jewel_num'=>1])->asArray()->all();
        $datas  = Day::find()->where(['jewel_num'=>2])->asArray()->all();
        return $this->render('index',['data'=>$model,'datas'=>$datas]);
    }
    
    
    
    /**
     * 添加 每日赠送天数
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Day();
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
     * 每日签到修改修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Day::findOne($id);
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
    
        $JSON = json_decode($model->gold_num,true);
        $data  =[];
        $re = Day::$give;
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$key]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        //var_dump($v['toolId']);
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
               
                /*if ($model->type==2 && $model->give_type!=='1'){
                    return ['code'=>0,'message'=>'固定使用奖励数值只能是金币'];
                }
                if ($model->type==1){
                    if ($model->give_type){
                        $model->give_type =implode('',$model->give_type);
                        if(($model->give_type=='1') && ($model->jewel_num || $model->salvo_num || !$model->gold_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='2' && ($model->gold_num || $model->salvo_num  || !$model->jewel_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='3' && ($model->gold_num || $model->jewel_num || !$model->salvo_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='12' && $model->salvo_num){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='12' && !($model->gold_num && $model->jewel_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='13' && $model->jewel_num){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='13' && !($model->gold_num && $model->salvo_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='23' && $model->gold_num){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='23' && !($model->jewel_num && $model->salvo_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                        if($model->give_type=='123' && !($model->gold_num && $model->jewel_num && $model->salvo_num)){
                            return ['code'=>0,'message'=>'类型和数量对应'];
                        }
                    }elseif($model->gold_num ||$model->jewel_num ||$model->salvo_num){
                        return ['code'=>0,'message'=>'类型和数量对应'];
                    }
        
                }*/
        
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
        $model  = Day::findOne($id);
        $day =  Day::find()->select(['day'])->where(['jewel_num'=>$model->jewel_num])->orderBy('day DESC')->limit(1)->one();
        if ($day){
            if ($day->day>$model->day){
                return ['code'=>0,'message'=>'删除只能从最大的天数删除'];
            }
        }
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/control/deleteSign';
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
        $model = Day::findOne($id);
        $JSON = json_decode($model->gold_num,true);
        $data  =[];
        $re = Day::$give;
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
    
    
    
    //同步数据
    public function actionGetday(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Day::GetDay();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    
    
    
}
