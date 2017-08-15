<?php

namespace backend\controllers;

use backend\models\Batteryrate;
use yii\web\Response;

class BatteryrateController extends ObjectController
{
    /**
     * @return string
     *  炮台命中率
     */
    public function actionIndex()
    {
        $data = Batteryrate::find()->asArray()->all();
        return $this->render('index',['data'=>$data]);
    }
    
    
    /**
     *  炮命中率
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Batteryrate::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->validate())
            {
                if ($model->rate>100 || $model->rate<0){
                    return ['code'=>0,'message'=>'炮台命中率无效'];
                }
                $model->rate=($model->rate*100);
                
               return $model->save()?['code'=>1,'message'=>'修改成功']:false;
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $model->rate=($model->rate/100);
        return $this->render('edit',['model'=>$model]);
    }

}
