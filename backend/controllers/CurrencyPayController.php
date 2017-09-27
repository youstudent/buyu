<?php

namespace backend\controllers;

use common\models\CurrencyPay;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\Response;

class CurrencyPayController extends ObjectController
{
    //充值货币管理
    public function actionIndex()
    {
       // CurrencyPay::GetCurrency();
        /*if (\Yii::$app->request->get('show') == 1) {
            $data = CurrencyPay::find()->where(['type' => 1])->orderBy('money ASC')->asArray()->all();
        } else if (\Yii::$app->request->get('show') == 2) {
            $data = CurrencyPay::find()->where(['type' => 2])->orderBy('money ASC')->asArray()->all();
        } else {*/
            $data = CurrencyPay::find()->orderBy('money ASC')->asArray()->all();
       // }
        return $this->render('index', ['data' => $data]);
    }
    
    
    /**
     * 添加充值货币
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new CurrencyPay();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                CurrencyPay::GetCurrency();
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $model->fold=1;
        return $this->render('add', ['model' => $model]);
    }
    
    /**
     * 充值货币修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = CurrencyPay::findOne($id);
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
        $JSON = json_decode($model->give_prize,true);
        $data  =[];
        $re = CurrencyPay::$give;
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
            if ($v>0){
                $type[]=$k;
            }
           
        }
        $model->type=$type;
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
        /**
         * 请求游戏服务端   删除数据
         */
        $model = CurrencyPay::findOne($id);
        $url = \Yii::$app->params['Api'].'/control/deletePay';
        $data=[];
        $data['id']=$id;
        $datas = Json::encode($data);
        $re = \common\services\Request::request_post_raw($url,$datas);
        if ($re['code']== 1){
           $model->delete();
            return ['code' => 1, 'message' => '删除成功'];
        }
    }
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = CurrencyPay::findOne($id);
        $JSON = json_decode($model->give_prize,true);
        $data  =[];
        $re = CurrencyPay::$give;
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
    
    
    //同步数据
    public function actionGetcurrency(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = CurrencyPay::GetCurrency();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
}
