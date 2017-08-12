<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/8/5
 * Time: 18:23
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Time extends ActiveRecord
{
    
    /**
     * @return \yii\db\Connection
     *
     * 这个类 重写db方法 , 获取不同的对象
     */
    public static function getDb()
    {
        return \Yii::$app->commondb;  // 使用名为 "secondDb" 的应用组件  重新定义主键
    }
    
    public static function tableName()
    {
        return 'playereverydaytask';
    }
    
}