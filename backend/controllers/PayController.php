<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\Agency;
use backend\models\AgencyPay;
use backend\models\UserPay;
use backend\models\Users;
use yii\data\Pagination;

class PayController extends ObjectController
{

    /**
     * 会员充值记录
     * @return string
     */
    public function actionUserPayLog()
    {
        $model = new Users();
        $data = $model->getPayLog(\Yii::$app->request->get());
        return $this->render('userPayLog',$data);
    }

    /**
     * 平台充值记录
     * 算法思路
     * @return string
     */
    public function actionAgencyPayLog()
    {
        $agency = new Agency();
        $agency->load(\Yii::$app->request->get());
        $agency->initTime();//初始化默认时间
        $model      = '';
       //var_dump(\Yii::$app->request->get());exit;
        //判断是否输入关键字
        if($agency->keyword != ''){
            $agencyInfo = Agency::find()->where($agency->searchWhere())->one();
            //查询代理是否存在
            if(isset($agencyInfo->id)) {
                $model = AgencyPay::find()->andWhere(['agency_id' => $agencyInfo->id]);
            } else{
                //不存在查询一个不存在代理充值记录
                $model = AgencyPay::find()->where(['id'=>-10]);
            }
        }else {$model = AgencyPay::find();}//没有关键字查询所有
        // 添加查询的时间条件
        $model->andWhere(['>=','time',strtotime($agency->starttime)])->andWhere(['<=','time',strtotime($agency->endtime)])->andWhere(['type'=>'充值']);
        $pages      = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data       = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('agencyPayLog',['model'=>$agency,'data'=>$data,'pages'=>$pages]);
    }
    
    
    /**
     * 平台扣除记录
     * 算法思路
     * @return string
     */
    public function actionAgencyOutLog()
    {
        $agency = new Agency();
        $agency->load(\Yii::$app->request->get());
        $agency->initTime();//初始化默认时间
        $model      = '';
        
        //判断是否输入关键字
        if($agency->keyword != ''){
            $agencyInfo = Agency::find()->where($agency->searchWhere())->one();
            //查询代理是否存在
            if(isset($agencyInfo->id)) {
                $model = AgencyPay::find()->andWhere(['agency_id' => $agencyInfo->id]);
            } else{
                //不存在查询一个不存在代理充值记录
                $model = AgencyPay::find()->where(['id'=>-10]);
            }
        }else {$model = AgencyPay::find();}//没有关键字查询所有
        // 添加查询的时间条件
        $model->andWhere(['>=','time',strtotime($agency->starttime)])->andWhere(['<=','time',strtotime($agency->endtime)])->andWhere(['type'=>'扣除']);
        $pages      = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data       = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('agencyOutLog',['model'=>$agency,'data'=>$data,'pages'=>$pages]);
    }
}