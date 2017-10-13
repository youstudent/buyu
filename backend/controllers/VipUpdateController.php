<?php

namespace backend\controllers;


use backend\models\Vipinfo;
use common\helps\getgift;
use common\helps\players;
use common\models\VipUpdate;
use Symfony\Component\DomCrawler\Field\InputFormField;
use yii\filters\AccessControl;
use yii\web\Response;

class VipUpdateController extends ObjectController
{
    
    //vip升级首页
    public function actionIndex()
    {
        $model  =Vipinfo ::find()->asArray()->all();
        return $this->render('index',['data'=>$model]);
    }
    
    
    /**
     *  增加 Vip 升级所需钻石
     * @return array|string
     */
    public function actionAdd()
    {
        
        $this->layout = false;
        $model = new VipUpdate();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                VipUpdate::GetVipBenefit();
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        return $this->render('add', ['model' => $model]);
    }
    
    
    /**
     *  修改 vip 升级所需钻石
     * @return array|string
     */
    public function actionEdit()
    {
        //players::actionPermission();
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Vipinfo::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $row = getgift::getType($model,'up','toolid','toolnum');
        $model->gift=$row['type'];
        $rows = getgift::getTypes($model,'','toolid','toolnum');
        $model->gifts=$rows['type'];
        $model->almsrate= $model->almsrate/100;
        $model->killrate= $model->killrate/100;
        return $this->render('edit',['model'=>$model,'data'=>$row['data'],'datas'=>$rows['data']]);
    }
    
    /**
     *  删除 vip等级升级 奖励
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        /**
         * 请求游戏服务端   删除数据
         */
        $url = \Yii::$app->params['Api'].'/control/deleteVIP';
        $data=[];
        $data['id']=$id;
        $re = \common\services\Request::request_post($url,$data);
        if ($re['code']== 1){
            return ['code'=>1,'message'=>'删除成功'];
        }
    }
    
    /**
     *  获取 vip等级 列表
     */
    public function actionGetvip(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = VipUpdate::GetVipBenefit();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
        
    }
    
    /**
     *  修改 vip 升级所需钻石
     * @return array|string
     */
    public function actionPrize()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Vipinfo::findOne($id);
        $row=  getgift::getType($model,'up','toolid','toolnum');
        $rows =  getgift::getType($model,'','toolid','toolnum');
        $model->gift=$row['type'];
        $model->gifts=$rows['type'];
        return $this->render('prize',['model'=>$model,'data'=>$row['data'],'datas'=>$rows['data']]);
    }
}
