<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace api\controllers;

use yii\web\Controller;
use yii\web\Response;
/**
 * Class ObjectController
 * @package backend\controllers
 */
class ObjectController extends Controller
{
    public $layout = false;

    /**
     * 检查是否有权限执行代码
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

    /**
     * api 接口统一返回的数据
     * @param $code -2 -1 0 1 2 3
     * @param $message
     * @param $data
     * @param string $time
     * @return mixed
     */
    public function returnAjax($code, $message, $data = [], $time = '')
    {
        if ($time == ''){
            $time = date("Y-m-d H:i:s");
        }
        $datas['code'] = $code;
        $datas['message'] = $message;
        $datas['time'] = $time;
        $datas['data'] = $data;
        return $datas;
    }
}