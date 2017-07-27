<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/14
 * Time: 09:58
 */

namespace api\controllers;


use Codeception\Module\REST;
use common\models\Touch;

class TouchController extends ObjectController
{
    //联系客户列表接口
    public function actionIndex(){
        $data = Touch::find()->select('phone,qq_number,hkmovie')->one();
        if ($data){
            return $this->returnAjax(1,'成功',$data);
        }
            return $this->returnAjax(0,'数据不存在');
    }
    
}