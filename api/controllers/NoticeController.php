<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace api\controllers;

use api\models\Notice;

class NoticeController extends ObjectController
{
    /**
     * 获取通知
     * @return mixed
     */
    public function actionGet()
    {
        $data = \Yii::$app->request->getQueryParam('data');
        if ($data==101){
            $location = '首页公告';
        }
        if ($data==102){
            $location = '首页滚动公告';
        }
        if ($data==103){
            $location = '房间滚动公告';
        }
    /*
        $data['101'] = '首页公告';
        $data['102'] = '首页滚动公告';
        $data['103'] = '房间滚动公告';*/
      
        if($location){
            $notice = Notice::find()->andWhere(['location'=>$location])->andWhere(['status'=>1])->select(['title','content','time','manage_name'])->one();
            return $this->returnAjax(1,'成功',$notice);
        }
        return $this->returnAjax(0,'参数不正确');
    }
}