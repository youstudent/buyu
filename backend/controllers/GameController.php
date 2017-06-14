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
            
            if($model->load(\Yii::$app->request->post()) && $model->save())
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
           $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }else{
            switch ($model->type){
                case 1:
                    $model->type='普通模式';
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