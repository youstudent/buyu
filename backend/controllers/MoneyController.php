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
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->manage_id    = \Yii::$app->session->get('manageId');
            $model->manage_name  = \Yii::$app->session->get('manageName');
            $model->updated_at=time();
            if($model->load(\Yii::$app->request->post()) && $model->save())
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('edit',['model'=>$model]);
    }
    
    
    public function actionTest(){
       theCsv::export('g_money');
    }

}
