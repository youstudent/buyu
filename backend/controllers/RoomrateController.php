<?php

namespace backend\controllers;

use backend\models\Roomrate;
use common\helps\players;
use yii\web\Request;
use yii\web\Response;

class RoomrateController extends ObjectController
{
    /**
     * @return string
     *  房间命中率
     */
    public function actionIndex()
    {
        $data = Roomrate::find()->asArray()->all();
        return $this->render('index',['data'=>$data]);
    }
    
    
    /**
     *  房间命中率修改
     * @return array|string
     */
    public function actionEdit()
    {
        players::actionPermission();
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Roomrate::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->validate())
            {
                if ($model->minrate<0 || $model->maxrate <0 || $model->minrate>100 || $model->maxrate>100 || $model->maxrate<=$model->minrate){
                    return ['code'=>0,'message'=>'命中率无效'];
                }
                $model->maxrate=($model->maxrate*100);
                $model->minrate=($model->minrate*100);
                return $model->save()?['code'=>1,'message'=>'修改成功']:false;
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        if ($model->muti ==1){
            $model->number='1倍场';
        }elseif ($model->muti ==2){
            $model->number='30倍场';
        }elseif ($model->muti ==3){
            $model->number='40倍场';
        }else{
            $model->number='鱼币场';
        }
        $model->maxrate=($model->maxrate/100);
        $model->minrate=($model->minrate/100);
        return $this->render('edit',['model'=>$model]);
    }

}
