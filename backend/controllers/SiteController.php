<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */

namespace backend\controllers;

use backend\models\Manage;
use backend\models\Shop;
use backend\models\Users;
use Yii;
use backend\models\AgencyPay;
use backend\models\UserPay;


if (!function_exists('cal_days_in_month'))
{
    function cal_days_in_month($calendar, $month, $year)
    {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}
if (!defined('CAL_GREGORIAN'))
    define('CAL_GREGORIAN', 1);
/**
 * Site controller
 */
class SiteController extends ObjectController
{


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Users::automatic();
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
        $data = AgencyPay::find()->andWhere([">",'time',$startTime])
                                 ->andWhere(["<","time",$endTime])->andWhere(['type'=>'充值','gold_config'=>1])
                                 ->orderBy("time ASC")->asArray()->all();
        for ($i=1;$i<=$dayNum;$i++){
            $oderNnm        = 0;
            $endValueTime   = strtotime(($year."-".$month."-".$i." 23:59:59"));
            $orderMonth[$i] = 0;
            foreach ($data as $key=>$value)
            {
                
                if($value['time'] <= $endValueTime)
                {
                    $orderMonth[$i] = ($oderNnm+$value['gold']);
                    $oderNnm        = ($oderNnm+$value['gold']);
                    unset($data[$key]);
                }elseif($value['time'] > $endValueTime){
                    continue;
                }
            }
        }
       // var_dump($oderNnm);exit;
        /**
         * 算出平台给用户充值的数量
         * 算法思路 同上
         */
        if(Yii::$app->params['backendPayUser'])
        {
            $data = AgencyPay::find()->andWhere([">",'time',$startTime])
                                   ->andWhere(["<","time",$endTime])
                                   ->andWhere(['type'=>'充值','gold_config'=>2])
                                   ->orderBy("time ASC")->asArray()->all();
            for ($i=1;$i<=$dayNum;$i++){
                $oderNnm = 0;
                $endValueTime   = strtotime(($year."-".$month."-".$i." 23:59:59"));
                $userOrder[$i] = 0;
                foreach ($data as $key=>$value)
                {
                    if($value['time'] <= $endValueTime)
                    {
                        $userOrder[$i] = ($oderNnm+$value['gold']);
                        $oderNnm       = ($oderNnm+$value['gold']);
                        unset($data[$key]);
                    }elseif($value['time'] > $endValueTime){
                        continue;
                    }
                }
            }
        }
        return $this->render('index',['monthOrderToDay'=>$orderMonth,'userOrderToDay'=>$userOrder]);
    }

}
