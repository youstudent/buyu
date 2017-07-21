<?php

namespace backend\controllers;

use common\models\Day;
use Symfony\Component\DomCrawler\Tests\Field\InputFormFieldTest;
use yii\web\Response;

class DayController extends \yii\web\Controller
{
    //每日签到首页
    public function actionIndex()
    {
        $model  = Day::find()->where(['type'=>1])->asArray()->all();
        $datas  = Day::find()->where(['type'=>2])->asArray()->all();
        return $this->render('index',['data'=>$model,'datas'=>$datas]);
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
    
    
}
