<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/13
 * Time: 16:32
 */

namespace backend\controllers;


use backend\models\Game;
use Codeception\Module\REST;
use common\services\Request;
use yii\debug\components\search\matchers\SameAs;
use yii\web\Response;

class GameController extends ObjectController
{
    
    public function actionIndex(){
        $data = Game::find()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    
    //修改 游戏设置
    public function actionUpdate(){
        $this->layout=false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model= Game::findOne(['id'=>$id]);
       
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            if($model->load(\Yii::$app->request->post()))
            {
                if ($model->type==3 && ($model->num>100 || $model->num<1)){
                    return ['code'=>0,'message'=>'机器人胜率必须在1-100之间'];
                }
             
                //匹配
                if ($model->type==1){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=setMatchPrice&matchPrice=".$model->num;
                }
                //房卡模式
                if ($model->type==2){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=setCardPrice&cardPrice=".$model->num;
                }
                //机器人胜率
                if ($model->type==3){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=setRebotWin&win=".$model->num;
                }
                //分享金币
                if ($model->type==4){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=setShareGold&shareGold=".$model->num;
                }
                $data = Request::request_get($url);
                if ($data['code'] == 1){
                    $model->save();
                    return ['code'=>1,'message'=>'修改成功'];
                }else{
                    return $data;
                }
                
            }
            $message = $model->getFirstErrors();
           $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }else{
            switch ($model->type){
                case 1:
                    $model->type='匹配模式';
                    break;
                case 2:
                    $model->type='房卡模式';
                    break;
                case 3:
                    $model->type='机器人胜率';
                    break;
                case 4:
                    $model->type='分享金币';
                    break;
            }
        }
        
        return $this->render('update',['model'=>$model]);
    }
    
    
   
    
}