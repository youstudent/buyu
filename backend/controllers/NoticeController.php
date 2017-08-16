<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */

namespace backend\controllers;

use backend\models\Notice;
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
        $data = new Notice();
        $model = $data::find();
        if (\Yii::$app->request->get('show') == 1) {
            $model->andWhere(["status" => 1]);
        } else if (\Yii::$app->request->get('show') == 0) {
            $model->andWhere(["status" => 0]);
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
     * 添加新的 公告
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Notice();
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
        $model = Notice::findOne($id);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $JSON = json_decode($model->number, true);
        $data = [];
        $re = Notice::$give;
        foreach ($JSON as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
            
        }
        $type = [];
        foreach ($data as $k => $v) {
            $type[] = $k;
        }
        $model->get_type = $type;
        if ($model->location == 2) {
            return $this->render('edit2', ['model' => $model, 'data' => $data]);
        }
        return $this->render('edit', ['model' => $model, 'data' => $data]);
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
        $model = Notice::findOne($id);
        $data = [];
        $data['id'] = $model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'] . '/gameserver/control/deleteNotice';
        $re = Request::request_post_raw($url, $datas);
        if ($re['code'] == 1) {
            $model->delete();
            return ['code' => 1, 'message' => '删除成功'];
        }
        return ['code' => 0, 'message' => '删除失败'];
        /*   if($model)
           {
               if($model->delete()) {
                   return ['code'=>1,'message'=>'删除成功'];
               }
               $messge = $model->getFirstErrors();
               $messge = reset($messge);
               return ['code'=>0,'message'=>$messge];
           }
           return ['code'=>0,'message'=>'删除的ID不存在'];*/
    }
    
    
    //奖品内容的查看
    public function actionPrize()
    {
        $this->layout = false;
        // RedeemCode::setShop();
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Notice::findOne($id);
        $JSON = json_decode($model->number, true);
        $data = [];
        $re = Notice::$give;
        foreach ($JSON as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$re[$key]] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$re[$v['toolId']]] = $v['toolNum'];
                    }
                }
            }
            
        }
        return $this->render('prize', ['model' => $model, 'data' => $data]);
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
        $model = Notice::findOne($id);
        return $this->render('content', ['model' => $model]);
    }
    
    
}