<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */

namespace backend\controllers;

use backend\models\Notice;
use backend\models\Notices;
use common\helps\getgift;
use common\services\Request;
use yii\data\Pagination;
use yii\helpers\Json;
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
        $data = new Notices();
        $model = $data::find()->andWhere(['status'=>1]);
        if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(["enable" => 1]);
        } else if (\Yii::$app->request->get('show') == 0) {
            $model->andWhere(["enable" => 0]);
        }
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->orderBy('id DESC')->all();
        
        return $this->render('index', ['data' => $data, 'pages' => $pages]);
    }
    
    
    /**
     * 添加新的 公告
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Notices();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        return $this->render('add', ['model' => $model]);
    }
    
    /**
     *  修改  公告
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Notices::findOne($id);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $row = getgift::getType($model);
        $model->gift=$row['type'];
        if ($model->noticetype == 2) {
            return $this->render('edit2', ['model' => $model, 'data' => $row['data']]);
        }
        return $this->render('edit', ['model' => $model, 'data' => $row['data']]);
    }
    
    /**
     *   删除  公告
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $re =  Notices::findOne(['id'=>$id]);
        if ($re){
            $re->enable=0;
            $re->status=0;
            if ($re->save(false)){
                return ['code' => 1, 'message' => '删除成功'];
            }
        }
        return ['code' => 0, 'message' => '数据不同步'];
        
    }
    
    /**
     * @return string
     */
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Notices::findOne($id);
        $data =  getgift::prize($model);
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    
    
    /**
     * 获取  同步公告数据
     */
    public function actionGetnotice()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Notice::GetNotice();
        if ($code == 1) {
            return ['code' => 1, 'message' => '同步成功'];
        }
        return ['code' => 0, 'message' => '同步失败'];
    }
    
    
    /**
     * 查看内容
     */
    public function actionContent()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Notices::findOne($id);
        return $this->render('content', ['model' => $model]);
    }
    
    
}