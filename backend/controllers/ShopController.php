<?php

namespace backend\controllers;

use backend\models\Shop;
use yii\web\Response;

class ShopController extends \yii\web\Controller
{
    public function actionIndex()
    {
        
        $data = Shop::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    
    /**
     * 商品修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Shop::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        return $this->render('edit',['model'=>$model]);
    }

}
