<?php
/**
 * Created by PhpStorm.
 * User: lrdoubleInt
 * Date: 2017/4/16
 * Time: 20:43
 */
namespace  frontend\controllers;

use frontend\models\Users;
use yii\web\Response;

class PayController extends ObjectController
{
    /**
     * 会员充值操作
     * @return string
     */
    public function actionPay()
    {
        if(\Yii::$app->request->get('game_id'))
        {
            $data = Users::find()->where(['game_id'=>\Yii::$app->request->get('game_id')])->one();
            $data->gold = $data->getGold();
            return $this->render('pay',['data'=>$data,'game_id'=>\Yii::$app->request->get('game_id')]);
        }
        return $this->render('pay');
    }

    /**
     * 确认充值操作
     * @return string
     */
    public function actionPay1()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = Users::findOne($id);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->pay(\Yii::$app->request->post())){
                return ['code'=>1,'message'=>'充值成功！'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        }
        $model->goldArr = $model->getGold();
        return $this->render('pay1',['model'=>$model,'game_id'=>\Yii::$app->request->get('game_id')]);
    }
}