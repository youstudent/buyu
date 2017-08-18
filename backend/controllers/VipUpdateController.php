<?php

namespace backend\controllers;


use common\models\VipUpdate;
use yii\web\Response;

class VipUpdateController extends ObjectController
{
    
    //vip升级首页
    public function actionIndex()
    {
        $model  =VipUpdate ::find()->asArray()->all();
        return $this->render('index',['data'=>$model]);
    }
    
    
    /**
     *  增加 Vip 升级所需钻石
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new VipUpdate();
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
     *  修改 vip 升级所需钻石
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = VipUpdate::findOne($id);
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
        $give_day = json_decode($model->give_day,true);  // 每日赠送礼包
        $give_upgrade = json_decode($model->give_upgrade,true);  //升级礼包
        $datas = [];
        $data  =[];
        $re = VipUpdate::$give;   //获取所有礼包
        $res = VipUpdate::$give_day;   //获取所有礼包
        /**
         *  解析每日礼包
         */
        foreach ($give_day as $key=>$value){
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
        $type=[];  //取出礼包的 key值
        foreach($data as $k=>$v) {
            $type[] = $k;
        }
        $i = 9;
        /**
         *   解析升级礼包
         */
        
        foreach ($give_upgrade as $key=>$value){
            if (array_key_exists($key.$i,$res)){
                $datas[$key.$i]=$value;
            }
            if(is_array($value)){
              
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'].$i,$res)){
                        $datas[$v['toolId'].$i]=$v['toolNum'];
                    }
                }
            }
        
        }
           $give_upgrade=[];
        foreach($datas as $k=>$v){
            $give_upgrade[]=$k;
        }
        //burst    alms_num
        $model->burst= $model->burst/100;
        $model->alms_rate=$model->alms_rate/100;
        $model->give_day=$type;  //每日礼包key值
        $model->give_upgrade=$give_upgrade;  //升级礼包key值
        return $this->render('edit',['model'=>$model,'data'=>$data,'datas'=>$datas]);
    }
    
    /**
     *  删除 vip等级升级 奖励
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
        $url = \Yii::$app->params['Api'].'/control/deleteVIP';
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
  /*  public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = VipUpdate::findOne($id);
        $JSON = json_decode($model->give_day,true);
        $JSONS = json_decode($model->give_upgrade,true);
        $data  =[];
        $datas = [];
        $re = VipUpdate::$give;
        foreach ($JSON as $key=>$value){
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        //var_dump($v['toolId']);
                        $data[$re[$v['toolId']]]=$v['toolNum'];
                    }
                }
            }
            if (array_key_exists($key,$re)){
                $data[$re[$key]]=$value;
            }
         
        }
        foreach ($JSONS as $key=>$value){
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        //var_dump($v['toolId']);
                        $datas[$re[$v['toolId']]]=$v['toolNum'];
                    }
                }
            }
            if (array_key_exists($key,$re)){
                $datas[$re[$key]]=$value;
            }
        
        }
    
        return $this->render('prize',['model'=>$model,'data'=>$data,'datas'=>$datas]);
    }*/
    
    
    /**
     *  获取 vip等级 列表
     */
    public function actionGetvip(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = VipUpdate::GetVipBenefit();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
        
    }
    
    /**
     *  修改 vip 升级所需钻石
     * @return array|string
     */
    public function actionPrize()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = VipUpdate::findOne($id);
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
        $give_day = json_decode($model->give_day,true);  // 每日赠送礼包
        $give_upgrade = json_decode($model->give_upgrade,true);  //升级礼包
        $datas = [];
        $data  =[];
        $re = VipUpdate::$give;   //获取所有礼包
        $res = VipUpdate::$give_day;   //获取所有礼包
        /**
         *  解析每日礼包
         */
        foreach ($give_day as $key=>$value){
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
        $type=[];  //取出礼包的 key值
        foreach($data as $k=>$v) {
            $type[] = $k;
        }
        $i = 9;
        /**
         *   解析升级礼包
         */
        
        foreach ($give_upgrade as $key=>$value){
            if (array_key_exists($key.$i,$res)){
                $datas[$key.$i]=$value;
            }
            if(is_array($value)){
                
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'].$i,$res)){
                        $datas[$v['toolId'].$i]=$v['toolNum'];
                    }
                }
            }
            
        }
        $give_upgrade=[];
        foreach($datas as $k=>$v){
            $give_upgrade[]=$k;
        }
        $model->give_day=$type;  //每日礼包key值
        $model->give_upgrade=$give_upgrade;  //升级礼包key值
        return $this->render('Prize',['model'=>$model,'data'=>$data,'datas'=>$datas]);
    }
}
