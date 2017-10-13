<?php

namespace backend\controllers;

use backend\models\Level;
use backend\models\Signprize;
use common\helps\getgift;
use common\models\Day;
use common\services\Request;
use Symfony\Component\DomCrawler\Tests\Field\InputFormFieldTest;
use yii\helpers\Json;
use yii\web\Response;

class DayController extends ObjectController
{
    //每日签到首页
    public function actionIndex()
    {
        $model  = Signprize::find()->where(['level'=>1])->asArray()->all();
        $datas  = Signprize::find()->where(['level'=>2])->asArray()->all();
        return $this->render('index',['data'=>$model,'datas'=>$datas]);
    }
    
    
    
    /**
     * 添加 每日赠送天数
     * @return array|string
     */
    public function actionAdd()
    {
        $this->layout = false;
        $model = new Signprize();
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
     * 每日签到修改修改操作操作
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Signprize::findOne($id);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $row = getgift::getType($model,'','toolid','toolnum');
        $model->gift=$row['type'];
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
        $id     = \Yii::$app->request->get('id');
        $model  = Signprize::findOne($id);
        $day =  Signprize::find()->select(['day'])->where(['level'=>$model->level])->orderBy('day DESC')->limit(1)->one();
        if ($day){
            if ($day->day>$model->day){
                return ['code'=>0,'message'=>'删除只能从最大的天数删除'];
            }
        }
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
        $model = Signprize::findOne($id);
        $data =  getgift::prize($model,'','toolid','toolnum');
        return $this->render('prize',['model'=>$model,'data'=>$data]);
    }
}
