<?php

namespace backend\controllers;

use common\models\Experience;
use common\services\Request;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class ExperienceController extends ObjectController
{
    public function actionIndex()
    {
        $model = Experience::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
    
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('grade ASC')->asArray()->all();
    
        return $this->render('index',['pages'=>$pages,'data'=>$data]);
    }
    
    
    /**
     * 添加
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Experience();
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
        $model->grade =  $model->getGrade();
        return $this->render('add',['model'=>$model]);
    }
    
    
    
    /**
     * 修改操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Experience::findOne($id);
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
        $JSON = json_decode($model->number,true);
        $data  =[];
        $re = Experience::$give;
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
        $model->give_type=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data]);
    }
    
    /**
     *  删除操作
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $model = Experience::findOne($id);
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/control/deleteLevel';
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
        $model = Experience::findOne($id);
        $JSON = json_decode($model->number,true);
        $data  =[];
        $re = Experience::$give;
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
    
    
    public function actionGetexperience(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Experience::GetExperience();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
        
        
    }

}
