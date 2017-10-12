<?php

namespace backend\controllers;

use backend\models\Contract;
use common\models\Touch;
use yii\web\Response;

class TouchController extends ObjectController
{
    public function actionIndex()
    {
        $data = Contract::find()->asArray()->all();
        
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
        $model = Contract::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->createtime=date('Y-m-d H:i:s');
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
