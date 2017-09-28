<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/9/12
 * Time: 16:11
 */

namespace backend\controllers;


use backend\models\Redpacket;
use yii\data\Pagination;
use yii\web\Response;

class RedpacketController extends ObjectController
{
    /**
     * 初始化显示列表
     * @return string
     */
    public function actionIndex()
    {
        $data = new Redpacket();
        $model = $data::find();
        if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(["type"=>1]);
        } elseif (\Yii::$app->request->get('show') == 2) {
            $model->andWhere(["type"=>2]);
        }elseif (\Yii::$app->request->get('show') == 3) {
            $model->andWhere(["type"=>3]);
        }elseif (\Yii::$app->request->get('show') == 4) {
            $model->andWhere(["type"=>4]);
        }elseif (\Yii::$app->request->get('show') == 5) {
            $model->andWhere(["type"=>5]);
        }
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('index', ['data' => $data, 'pages' => $pages]);
    }
    
    
    /**
     * 添加鱼任务
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Redpacket();
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
     *  修改红包鱼
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Redpacket::findOne($id);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $model->rate =$model->rate/100;
        $model->minnum=$model->minnum/100;
        $model->maxnum=$model->maxnum/100;
        $model->dropmax=$model->dropmax/100;
        $model->dropmin=$model->dropmin/100;
        return $this->render('edit', ['model' => $model]);
    }
    
    
    /**
     * 删除红包鱼
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $model = Redpacket::findOne($id);
        return $model->delete()?['code' => 1, 'message' => '删除成功']:['code' => 0, 'message' => '删除失败'];
    }
    
}