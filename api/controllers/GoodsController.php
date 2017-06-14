<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/13
 * Time: 15:20
 */

namespace api\controllers;




use api\models\Goods;
use Codeception\Module\REST;

class GoodsController extends ObjectController
{
    // 兑换数据的添加
    public function actionAdd()
    {
        if(\Yii::$app->request->isPost)
        {
            $model = new Goods();
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