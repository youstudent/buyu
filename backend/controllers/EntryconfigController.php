<?php

namespace backend\controllers;

use backend\models\Entryconfig;
use yii\web\Response;

class EntryconfigController extends \yii\web\Controller
{
    
    /**
     * 配置数据
     * @return string
     */
    public function actionIndex()
    {
        $data = Entryconfig::find()->select(['cjuseable','fishgolduseable','id'])->asArray()->one();
        return $this->render('index',['data'=>$data]);
    }
    
    /**
     *  修改配参数状态
     * @return array
     */
    public function actionStatus()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $entryconfig = new Entryconfig();
        if ( $entryconfig->editStatus (\Yii::$app->request->get())){
            return ['code'=>1,'message'=>'操作成功!'];
        }
            return ['code'=>0,'message'=>'操作失败!'];
    }
    
    
    /**
     *  功能开关列表
     */
    public function actionFeature(){
        $data = Entryconfig::find()->asArray()->one();
        return $this->render('new_index',['data'=>$data]);
    }

}
