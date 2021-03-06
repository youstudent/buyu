<?php


namespace frontend\controllers;

use common\models\Familyplayer;
use common\models\Familyrecord;
use common\models\Player;
use frontend\models\Agency;
use frontend\models\AgencyPay;
use frontend\models\UserPay;
use frontend\models\Users;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends ObjectController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        #获取用户基本资料
        $model = Agency::findOne(\Yii::$app->session->get('agencyId'));
        $year       = date('Y');
        $month      = date('m');
        $dayNum     = cal_days_in_month(CAL_GREGORIAN,$month,$year);//算当前月份的天数
        $startTime  = strtotime(($year."-".$month));//算出开始查询的时间戳
        $endTime    = strtotime(($year."-".$month."-".$dayNum." 23:59:59"));//算出结束查询的时间戳
        $orderMonth = [];//平台给代理的充值总金额
        $userOrder  = [];//平台给用户的充值总金额

        /**
         * 算出平台给代理充值的金额
         * 算法思路:
         *      1.查询这个月的所有销售记录
         *      2.循环并累加一个天
         *      3.进入二次循环并判断时间是否为当天、
         *          为当天并unset当前key、此操作为提高程序性能
         */
        $data = Familyrecord::find()->andWhere(['familyid'=>\Yii::$app->session->get('familyId')])
            ->andWhere([">",'time',date('Y-m-d H:i:s',$startTime)])
            ->andWhere(["<","time",date('Y-m-d H:i:s',$endTime)])->andWhere(['type'=>6])
            ->orderBy("time ASC")->asArray()->all();
        for ($i=1;$i<=$dayNum;$i++){
            $oderNnm        = 0;
            $endValueTime   = strtotime(($year."-".$month."-".$i." 23:59:59"));
            $orderMonth[$i] = 0;
            foreach ($data as $key=>$value)
            {
                if(strtotime($value['time']) <= $endValueTime)
                {
                    $orderMonth[$i] = ($oderNnm+$value['gold']);
                    $oderNnm        = ($oderNnm+$value['gold']);
                    unset($data[$key]);
                }elseif($value['time'] > $endValueTime){
                    continue;
                }
            }
        }
        

        /**
         * 算出平台给用户充值的数量
         * 算法思路 同上
         */
        $data = Familyrecord::find()->andWhere(['familyid'=>\Yii::$app->session->get('familyId')])
            ->andWhere([">",'time',date('Y-m-d H:i:s',$startTime)])
            ->andWhere(["<","time",date('Y-m-d H:i:s',$endTime)])->andWhere(['type'=>6])
            ->orderBy("time ASC")->asArray()->all();
        for ($i=1;$i<=$dayNum;$i++){
            $oderNnm = 0;
            $endValueTime   = strtotime(($year."-".$month."-".$i." 23:59:59"));
            $userOrder[$i] = 0;
            foreach ($data as $key=>$value)
            {
               
                if(strtotime($value['time']) <= $endValueTime)
                {
                    $userOrder[$i] = ($oderNnm+$value['diamond']);
                    $oderNnm       = ($oderNnm+$value['diamond']);
                    unset($data[$key]);
                }elseif($value['time'] > $endValueTime){
                    continue;
                }
            }
        }
    
        $family_id = \Yii::$app->session->get('familyId');
        //查询组员表
        $Family['gold']=0;
        $Family['diamond']=0;
        $Family['fishgold']=0;
        if ($FamilyPlayer= Familyplayer::find()->select(['sum(gold)','sum(diamond)','sum(fishgold)'])->where(['familyid'=>$family_id])->asArray()->one()){
            $Family['gold']=$FamilyPlayer['sum(gold)'];
            $Family['diamond']=$FamilyPlayer['sum(diamond)'];
            $Family['fishgold']=$FamilyPlayer['sum(fishgold)'];
        }
        return $this->render('index',['model'=>$model,'monthOrderToDay'=>$orderMonth,'userOrderToDay'=>$userOrder,'Family'=>$Family]);
    }

    /**
     * 修改密码
     * @return array|string
     */
    public function actionEditPass()
    {
        $this->layout = false;
        $model = Agency::findOne(\Yii::$app->session->get('agencyId'));
        if(\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->editPassword(\Yii::$app->request->post()))
            {
               
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('editPassword',['model'=>$model]);
    }
}
