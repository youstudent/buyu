<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

use backend\models\Notice;
use yii\web\Response;

/**
 * 通知操作控制类
 * Class NoticeController
 * @package backend\controllers
 */
class NoticeController extends ObjectController
{
    /**
     * 初始化显示列表
     * @return string
     */
    public function actionIndex()
    {

        if (\Yii::$app->request->get('show') == 1) {
            $data = Notice::find()->andWhere(["status" => 1])->asArray()->all();
        } elseif (\Yii::$app->request->get('show') == 2) {
            $data = Notice::find()->andWhere(["status" => 2])->asArray()->all();
        } else {
            $data = Notice::find()->asArray()->all();
        }

        return $this->render('index',['data'=>$data]);
    }


    /**
     * 添加新的通知
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Notice();
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
     * 通知修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Notice::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(\Yii::$app->request->post()) && $model->save())
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
     * 通知删除操作
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = Notice::findOne($id);
        if($model)
        {
            if($model->delete()) {
                return ['code'=>1,'message'=>'删除成功'];
            }
            $messge = $model->getFirstErrors();
            $messge = reset($messge);
            return ['code'=>0,'message'=>$messge];
        }
        return ['code'=>0,'message'=>'删除的ID不存在'];
    }
}