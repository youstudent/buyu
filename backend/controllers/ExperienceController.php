<?php

namespace backend\controllers;

use common\models\Experience;
use yii\data\Pagination;
use yii\web\Response;

class ExperienceController extends ObjectController
{
    public function actionIndex()
    {
        
        $model = Experience::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
    
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('grade ASC')->asArray()->all();
    
        return $this->render('index',['pages'=>$pages,'data'=>$data]);
    }
    
    
    /**
     * 添加
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Experience();
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
        $model = Experience::findOne($id);
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
        return $this->render('edit',['model'=>$model]);
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
        $model = Experience::findOne($id);
        if ($model) {
            if ($model->delete()) {
                return ['code' => 1, 'message' => '删除成功'];
            }
            $messge = $model->getFirstErrors();
            $messge = reset($messge);
            return ['code' => 0, 'message' => $messge];
        }
        
        
    }

}
