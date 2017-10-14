<?php

namespace backend\controllers;

use backend\models\Fishtask;
use common\helps\getgift;
use common\models\SignBoard;
use common\services\Request;
use Symfony\Component\DomCrawler\Field\InputFormField;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Response;

class SignBoardController extends ObjectController
{
    //捕鱼任务首页
    public function actionIndex()
    {
        $data = new Fishtask();
        $model = $data::find();
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' =>\Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
    
        return $this->render('index',['data'=>$data,'pages'=>$pages]);
    }
    
    
    /**
     *  添加 任务
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Fishtask();
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
     *  修改 任务
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Fishtask::findOne($id);
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
        $model->type=Fishtask::GetFishtype($model->fishid);
        $model->from=Fishtask::GetFishtype($model->fromfish);
        $model->rate=$model->rate/100;
        return $this->render('edit',['model'=>$model,'data'=>$row['data']]);
    }
    
    /**
     *   删除  公告
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = Fishtask::findOne($id);
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
        $model = Fishtask::findOne($id);
        $data =  getgift::prize($model,'','toolid','toolnum');
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
    /**
     *  $a = trim($model->from_fishing, "[");
    $b = trim($a, "]");
    $c = explode(",", $b);
    $model->from_fishing=$c;
     */

}
