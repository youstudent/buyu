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
        $location='';
        if ($data==101){
            $location = '系统公告';
        }
        if ($data==102){
            $location = '游戏公告';
        }
    /*
        $data['101'] = '首页公告';
        $data['102'] = '首页滚动公告';
        $data['103'] = '房间滚动公告';*/
      
        if($location){
            $notice = Notice::find()->andWhere(['location'=>$location])->andWhere(['status'=>1])->select(['title','content','time','manage_name','number'])->one();
            $data = [];
            if ($notice){
                $Json = json_decode($notice->number);
                $datas = [];
                //循环数组  将奖品道具和和金币钻石宝石分开
                $tools = [];
                $i = 0;
                $tool = [];
                foreach ($Json as $k => $value) {
                    if (is_numeric($k)) {
                        $datas[$k] = $value;
                        $tool['toolId'] = $k;
                        $tool['toolNum'] = $value;
                        $tools[$i] = $tool;
                        $i++;
                    } else {
                        $data[$k] = $value;
                    }
        
                }
                //组装 游戏服务端需要的数据  格式
                $data['title']=$notice->title;
                $data['content']=$notice->content;
                $data['tools'] = $tools;
            }
           
            return $this->returnAjax(1,'成功',$data);
        }
        return $this->returnAjax(0,'参数不正确');
    }
}