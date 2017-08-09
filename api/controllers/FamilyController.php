<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/8/9
 * Time: 17:27
 */

namespace api\controllers;


use common\models\AddFamily;

class FamilyController extends ObjectController
{
    /**
     * 添加玩家申请
     *
     */
    public function actionAdd(){
        if(\Yii::$app->request->isPost)
        {
            $model = new AddFamily();
            if($model->add(\Yii::$app->request->post())){
                return $this->returnAjax(1,'成功',[]);
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return $this->returnAjax(0,$message,[]);
        }
        return $this->returnAjax(0,'Please submit with POST');
        
        
    }
    
}