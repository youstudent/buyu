<?php

namespace backend\controllers;

use backend\models\Redpacketrecord;

class RedpacketrecordController extends ObjectController
{
    
    /**
     * 获得红包
     * @return string
     */
    public function actionGet()
    {
        $model = new Redpacketrecord();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('get',$data);
    }
    
    /**
     * 消耗红包
     * @return string
     */
    public function actionLose()
    {
        $model = new Redpacketrecord();
        $data = $model->getLose(\Yii::$app->request->get());
        return $this->render('lose',$data);
    }

}
