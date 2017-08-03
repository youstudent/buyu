<?php

namespace backend\controllers;

use backend\models\Ranking;
use yii\web\ViewAction;

class RankingController extends \yii\web\Controller
{
    /**
     * @return string
     *  获取排行榜 数据
     */
    public function actionIndex()
    {
        $model = new Ranking();
        $row = \Yii::$app->request->get();
        $rows=$row['Ranking'];
        $data = Ranking::GetRanking($rows['type'],$rows['province']);
        $model->province = $rows['province'];
        if ($rows['type'] == 1){
            return $this->render('index',['data'=>$data,'model'=>$model]);
        }
        if ($rows['type'] == 2){
            return $this->render('index2',['data'=>$data,'model'=>$model]);
        }
            return $this->render('index3',['data'=>$data,'model'=>$model]);
        
    }
    
    
    

}
