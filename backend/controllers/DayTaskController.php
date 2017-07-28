<?php

namespace backend\controllers;

use common\models\DayTask;
use yii\web\Response;

class DayTaskController extends \yii\web\Controller
{
    /**
     *  捕鱼每日任务列表
     * @return string
     */
    public function actionIndex()
    {
        $data = DayTask::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    /**
     *  修改每日任务
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne($id);
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
        $JSON = json_decode($model->package,true);
        $data  =[];
        $re = DayTask::setFishing();
        $i='z';
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key.$i,$re)){
                $data[$key.$i]=$value;
            }
            /*if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$v['toolId']]=$v['toolNum'];
                    }
                }
            }*/
        }
        
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        
        $JSONS = json_decode($model->fish_number,true);
        $datas  =[];
        $res = DayTask::$fishing;
            /**
             *   解析升级礼包
             */
          foreach ($JSONS as $key=>$value){
                if (array_key_exists($key,$res)){
                    $datas[$key]=$value;
                }
            }
             $types=[];
            foreach($datas as $k=>$v){
                $types[]=$k;
            }
        /*$a = trim($model->from_fishing, "[");
        $b = trim($a, "]");
        $c = explode(",", $b);
        $model->from_fishing=$c;*/
        $model->fish_number=$types;
        $model->package=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data,'datas'=>$datas]);
    }

}
