<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\Agency;
use backend\models\Rebate;
use backend\models\RebateConf;
use yii\web\Response;
use yii\data\Pagination;

/**
 * 返利操作模型
 * Class RebateController
 * @package backend\controllers
 */
class RebateController extends ObjectController
{


    /**
     * 返利记录搜索
     * @return string
     */
    public function actionIndex()
    {
        $agency = new Agency();
        $agency->load(\Yii::$app->request->get());
        $agency->initTime();
        $model      = '';
        if($agency->keyword != ''){
            $agencyInfo = Agency::find()->where($agency->searchWhere())->one();
            if(isset($agencyInfo->id)) $model = Rebate::find()->where(['agency_id'=>$agencyInfo->id]);
                else $model = Rebate::find()->where(['id'=>-10]);
        }else {$model = Rebate::find();}
        $model->andWhere(['>=','time',strtotime($agency->starttime)])->andWhere(['<=','time',strtotime($agency->endtime)]);
        $pages      = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data       = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('index',['model'=>$agency,'data'=>$data,'pages'=>$pages]);
    }

    /**
     * 返钻比例设置
     * @return array|string
     */
    public function actionSetting()
    {
        $this->layout = false;
        $model  = RebateConf::findOne(1);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->save())
            {
                return ['code'=>1,'message'=>'修改成功'];
            }else{
                $message = $model->getFirstErrors();
                $message = reset($message);
                return ['code'=>0,'message'=>$message];
            }
        }
        return $this->render('setting',['model'=>$model]);
    }
}