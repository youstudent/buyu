<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\controllers;

/**
 * 数据统计类 暂时未使用
 * Class InfoController
 * @package backend\controllers
 */
class InfoController extends ObjectController
{
    public function actionUsers()
    {
        return $this->render('users');
    }

    public function actionAgency()
    {
        return $this->render('agency');
    }
    public function actionSales()
    {
        return $this->render('sales');
    }
}