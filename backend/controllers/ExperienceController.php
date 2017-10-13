<?php

namespace backend\controllers;

use backend\models\Level;
use common\helps\getgift;
use common\models\Experience;
use common\services\Request;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class ExperienceController extends ObjectController
{
    public function actionIndex()
    {
        $model = Level::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
    
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('level ASC')->asArray()->all();
    
        return $this->render('index',['pages'=>$pages,'data'=>$data]);
    }
    
    
    /**
     * 添加
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Level();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->add(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        //FLOOR(((等级-1)^3+20)/5*((等级-1)*2+20)+30,30)
        $model->level =  $model->getGrade();
        $model->ex = Experience::ex($model->level);
        //=FLOOR(((等级-1)^3+20)/5*((等级-1)*2+20)+30,30)
        return $this->render('add',['model'=>$model]);
    }
    
    
    
    /**
     * 修改操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Level::findOne($id);
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
        $row = getgift::getType($model,'','toolid','toolnum');
        $model->gift=$row['type'];
        return $this->render('edit',['model'=>$model,'data'=>$row['data']]);
    }
    
    /**
     *  删除操作
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $model = Level::findOne($id);
        if ($model){
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'删除失败'];
        
    }
    
    
    //奖品内容的查看
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Level::findOne($id);
        $data =  getgift::prize($model,'','toolid','toolnum');
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    

}
