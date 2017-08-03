<?php

namespace backend\controllers;

use common\models\Money;
use yii\web\Response;
use m35\thecsv\theCsv;
class MoneyController extends ObjectController
{
    public function actionIndex()
    {
        $data = Money::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    
    /**
     * 货币修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Money::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('edit',['model'=>$model]);
    }
    
    
    /**
     *   导出 CSV格式数据
     */
    public function actionTest(){
       theCsv::export('g_money');
    }
    
    
    /**
     *   同步货币数据
     */
    public function actionGetMoney(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Money::GetMoney();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
        
        
    }

}
