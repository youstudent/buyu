<?php

namespace backend\controllers;

use common\models\VipBenefit;
use yii\web\Response;

class VipBenefitController extends ObjectController
{
    //vip首页
    public function actionIndex()
    {
        $model  = VipBenefit::find()->asArray()->all();
        return $this->render('index',['data'=>$model]);
    }
    
    /**
     *  增加 Vip等级每日福利包
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new VipBenefit();
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
     *  修改 vip等级每日福利包
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = VipBenefit::findOne($id);
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
        $re = VipBenefit::$give;
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
     *  删除 vip等级福利包
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        /**
         * 请求游戏服务端   删除数据
         */
        $url = \Yii::$app->params['Api'].'/gameserver/control/getpayinfo';
        $data=[];
        $data['id']=$id;
        $re = \common\services\Request::request_post($url,$data);
        if ($re['code']== 1){
            return ['code'=>1,'message'=>'删除成功'];
        }
        /*$model = VipBenefit::findOne($id);
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
        $model = VipBenefit::findOne($id);
        $JSON = json_decode($model->number,true);
        $data  =[];
        $re = VipBenefit::$give;
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        //var_dump($v['toolId']);
                        $data[$re[$v['toolId']]]=$v['toolNum'];
                    }
                }
            }
            
        }
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
}
