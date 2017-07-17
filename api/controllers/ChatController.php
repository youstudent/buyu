<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/14
 * Time: 16:26
 */

namespace api\controllers;


use common\models\Chat;

class ChatController extends ObjectController
{
    //获取聊天内容信息
    public function actionGetChat(){
        if(\Yii::$app->request->isPost)
        {
          if ($data=Chat::find()->select('content')->where(['status'=>1])->all()){
              return $this->returnAjax(1,'成功',$data);
          }
        }
        return $this->returnAjax(0,'Please submit with POST');
    }
    
}