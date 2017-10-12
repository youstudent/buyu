<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\AgencyDeduct;
use backend\models\Time;
use backend\models\Users;
use backend\models\UsersTime;
use common\models\Player;
use common\models\Ratio;
use common\services\Request;
use Yii;
use yii\web\Response;

/**
 * 游戏玩家管理类
 * Class UsersController
 * @package backend\controllers
 */
class UsersController extends ObjectController
{

    /**
     * 平台给玩家充值处理
     * @return array|string
     */
    public function actionPay()
    {
        $this->layout = false;
        if(Yii::$app->request->isPost)
        {
            /**
             * 设置返回为json格式
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Users();
            if($model->pay(Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>Yii::t('app','操作成功')];
            }
            /**
             * 获取model返回的错误
             */
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        /**
         * 查询用户并返回给页面进行渲染
         */
        $model = Users::findOne(Yii::$app->request->get('id'));
        $model->goldArr = $model->getGold();
        return $this->render('payModal',['model'=>$model]);
    }
    
    
    /**
     * 平台给玩家扣除处理
     * @return array|string
     */
    public function actionOut()
    {
        $this->layout = false;
        if(Yii::$app->request->isPost)
        {
            /**
             * 设置返回为json格式
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Users();
            if($model->out(Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>Yii::t('app','操作成功')];
            }
            /**
             * 获取model返回的错误
             */
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        /**
         * 查询用户并返回给页面进行渲染
         */
        $model = Users::findOne(Yii::$app->request->get('id'));
        $model->goldArr = $model->getGold();
        return $this->render('outModal',['model'=>$model]);
    }
    
    

    /**
     * 显示用户列表
     * @return string
     */
    public function actionList()
    {
        Users::automatic();// 查找解封时间超过当前时间的用户进行清理
        $model = new Users();
        $data = $model->getList(Yii::$app->request->get());
        return $this->render('list',$data);
    }
    
    /**
     *  跟新数据
     */
   

    /**
     * 显示用户的充值记录表
     * @return string
     */
    public function actionPayLog()
    {
        $model = new Users();
        $data = $model->getPayLog(Yii::$app->request->get());
        return $this->render('pay_log',$data);
    }
    
    /**
     * 显示用户的扣除记录表
     * @return string
     */
    public function actionPayOut()
    {
        $model = new Users();
        $data = $model->getPayOut(Yii::$app->request->get());
        // var_dump($data);exit;
        return $this->render('pay_log',$data);
    }

    /**
     * 显示用户消费记录
     * @return string
     */
    public function actionOutLog()
    {
        $model = new Users();
        $data = $model->getOutLog(Yii::$app->request->get());
        return $this->render('out_log',$data);
    }

    /**
     * 显示用户战绩
     * @return string
     */
    public function actionExploits()
    {
        $model = new Users();
        $data = $model->getExploits(Yii::$app->request->get());
        return $this->render('exploits',$data);
    }
    
    /**
     * 处理 账号启封和停封的问题
     */
    public function actionPass(){
        $model =  new Users();
        $data = $model->pass(Yii::$app->request->get());
        if ($data){
            return $this->redirect(['users/list']);
        }
        
    }
    
    
    //玩家 和平台的扣除记录
    public function actionDeduct(){
        $model = new AgencyDeduct();
        $data = $model->getDeductLog(Yii::$app->request->get());
        return $this->render('deduct',$data);
    }
    
    
    //设置解封时间
    public function actionUnsetTime(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Player::findOne(['id'=>$id]);
        
        if(Yii::$app->request->isPost)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post())){
                    $time = strtotime($model->unset_time);
                    if ($time-time()<0){
                        return ['code'=>0,'message'=>'停封时间不能小于当前时间'];
                    }
                    $UsersTime = new UsersTime();
                    $UsersTime->game_id=$model->id;
                    $UsersTime->time=$time;
                    $UsersTime->save(false);
                    $datas['playerId']=$model->id;
                    $datas['banTime']=($time-time())*1000;//将时间戳转化成毫秒
                    $result = Request::request_post(\Yii::$app->params['Api'].'/control/ban',$datas);
                    if ($result['code'] == 1){
                        return ['code'=>1,'message'=>Yii::t('app','操作成功')];
                    }
            }
        $message = $model->getFirstErrors();
        $message = reset($message);
        return ['code'=>0,'message'=>$message];
        }
        return $this->render('unset_time',['model'=>$model]);
    }
    
    
    //将账号 加入黑名单或解除
    public function actionBlack()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Users();
        if ($model->black(Yii::$app->request->get('id'),Yii::$app->request->get('status'))){
            return ['code'=>1,'message'=>'操作成功'];
        }
            /**
             * 获取model返回的错误
             */
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
    }
    
    
    
    //黑名单列表
    public function actionBlacklist(){
        $model = new Users();
        $data  =$model->blacklist(Yii::$app->request->get());
        return $this->render('blacklist',$data);
    }
    
    
    public function actionBan(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Users;
        if ($model->ban(\Yii::$app->request->get('id'))){
            return ['code'=>1,'message'=>'操作成功'];
        }
    
        /**
         * 获取model返回的错误
         */
        $message = $model->getFirstErrors();
        $message = reset($message);
        return ['code'=>0,'message'=>$message];
        
    }
    
    
    //充值 比例设置
    public function actionRatio(){
        $model  = Ratio::find()->asArray()->all();
        return $this->render('ratio',['data'=>$model]);
    }
    
    /**
     *  一键同步数据
     */
    public function actionUpdateUsers(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $re = Users::UpdateUsers();
        return ['code'=>$re,'message'=>'同步成功'];
    }
    
   
}
