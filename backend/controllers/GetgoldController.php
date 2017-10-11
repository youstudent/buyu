<?php

namespace backend\controllers;

use backend\models\Almsgold;
use common\models\Battery;
use common\models\GetGold;
use yii\web\Response;

class GetgoldController extends ObjectController
{
    /**
     * 救济金表
     * @return string
     */
    public function actionIndex()
    {
        $data = Almsgold::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    /**
     * 领取金币修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Almsgold::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
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
    
}
