<?php

namespace backend\controllers;

use backend\models\Pay;
use common\helps\getgift;
use common\models\CurrencyPay;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\Response;

class CurrencyPayController extends ObjectController
{
    //充值货币管理
    public function actionIndex()
    {
         $data = Pay::find()->orderBy('money ASC')->asArray()->all();
        return $this->render('index', ['data' => $data]);
    }
    
    
    /**
     * 添加充值货币
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Pay();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $model->firstdouble=1;
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
        $model = Pay::findOne($id);
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
        $row = getgift::getType($model,'send','toolid','toolnum');
        $model->gift=$row['type'];
        return $this->render('edit',['model'=>$model,'data'=>$row['data']]);
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
        $model = Pay::findOne($id);
        if ($model){
           $model->delete();
            return ['code' => 1, 'message' => '删除成功'];
        }
        return ['code' => 0, 'message' => '数据异常'];
    }
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model =Pay::findOne($id);
        $data =  getgift::prize($model,'send','toolid','toolnum');
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
}
