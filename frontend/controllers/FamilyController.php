<?php

namespace frontend\controllers;

use common\models\AddFamily;
use common\models\Family;
use common\models\Familyplayer;
use common\models\Familyrecord;
use common\models\Updowninfo;
use yii\data\Pagination;
use yii\web\Response;

class FamilyController extends \yii\web\Controller
{
    
    /**
     *  家族申请列表
     * @return string
     */
    public function actionList()
    {
        $model = new Familyplayer();
        $data = $model->getApply(\Yii::$app->request->get());
        return $this->render('apply',$data);
    }
    
    
    /**
     *  家族组员管理
     */
    public function actionGetSon(){
        $model = new Familyplayer();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('index',$data);
    }
    
    
    /**
     *  家族公告
     */
    public function actionNotice(){
        $data = Family::findOne(['id'=>\Yii::$app->session->get('familyId')]);
        return $this->render('notice',['data'=>$data]);
    }
    
    /**
     * 修改家族公告
     */
    public function actionNoticeEdit(){
        $this->layout = false;
        $data = Family::findOne(['id'=>\Yii::$app->session->get('familyId')]);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $data->scenario='edit';
            if($data->load(\Yii::$app->request->post()) && $data->save() ){
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $data->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        return $this->render('notice-edit',['model'=>$data]);
    }
    
    
    /**
     *  个人信息
     */
    public function actionGetMessage(){
        $data  = Family::findOne(['id'=>\Yii::$app->session->get('familyId')]);
        return $this->render('message',['data'=>$data]);
    }
    
    
    /**
     *  玩家上下分,查看
     */
    public function actionUpAndDown(){
       
        $data= new Familyrecord();
        $updown = \Yii::$app->request->get();
        $model = $data::find()->andWhere(['familyid'=>\Yii::$app->session->get('familyId')]);
        if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(['type'=>6]);
        }elseif (\Yii::$app->request->get('show') == 0){
            $model->andWhere(['type'=>5]);
        }else{
            $model->andWhere(['type'=>[5,6]]);
        }
        
        
       if (array_key_exists('playerid',$updown)){
          $model->andWhere(['playerid'=>$updown['playerid']]);
        }
        
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
        //玩家总上分
        $rows = Familyrecord::find()->select(['sum(gold)','sum(diamond)'])->andWhere(['familyid'=>\Yii::$app->session->get('familyId')])->andWhere(['type'=>6])->asArray()->one();
        
        //玩家总下分
        $row = Familyrecord::find()->select(['sum(gold)','sum(diamond)'])->andWhere(['familyid'=>\Yii::$app->session->get('familyId')])->andWhere(['type'=>5])->asArray()->one();
        return $this->render('up-and-down',['data'=>$data,'pages'=>$pages,'rows'=>$rows,'row'=>$row]);
    }
    
    /**
     *  玩家加入家族通过还是拒绝
     */
        public function actionPass(){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Familyplayer();
            if ($model->pass(\Yii::$app->request->get('id'),\Yii::$app->request->get('status'))){
                return ['code'=>1,'message'=>\Yii::t('app','操作成功')];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
 
     /**
      * 踢出该玩家
      */
     public function actionKick(){
         \Yii::$app->response->format = Response::FORMAT_JSON;
         $model = new Familyplayer();
         if ($model->kickOut(\Yii::$app->request->get('id'),\Yii::$app->request->get('status'))){
             return ['code'=>1,'message'=>\Yii::t('app','操作成功')];
         }
         $message = $model->getFirstErrors();
         $message = reset($message);
         return ['code'=>0,'message'=>$message];
         
         
     }
}
