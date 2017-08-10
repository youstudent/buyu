<?php

namespace frontend\controllers;

use common\models\AddFamily;
use common\models\Family;
use common\models\Familyplayer;
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
        $model = new AddFamily();
        $data = $model->getList(\Yii::$app->request->get());
        return $this->render('list',$data);
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
     *  玩家上下分
     */
    public function actionUpAndDown(){
       
        $data= new Updowninfo();
        $updown = \Yii::$app->request->get();
        $model = $data::find()->andWhere(['familyid'=>\Yii::$app->session->get('familyId')]);
        /*if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(["updown"=>1]);
        }
         if (\Yii::$app->request->get('show') == 0) {
            $model->andWhere(["updown"=>0]);
        }
       if (array_key_exists('playerid',$updown)){
          $model->andWhere(['playerid'=>$updown['playerid']]);
        }
        */
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
        var_dump($data->users);exit;
        return $this->render('up-and-down',['data'=>$data,'pages'=>$pages]);
    }

}
