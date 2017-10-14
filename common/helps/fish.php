<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/10/14
 * Time: 11:01
 */

namespace common\helps;


use yii\helpers\ArrayHelper;

class fish
{
    public static $fishType=[1=>'小鱼',2=>'中鱼',3=>'大鱼',4=>'金鱼',5=>'BOSS'];
    /**
     *  获取所有的鱼,  鱼ID 对应名字
     * @return array
     */
    public static function getFish(){
        $data = \backend\models\Fish::find()->asArray()->all();
        $new_datas = ArrayHelper::map($data,'id','name');
        return $new_datas;
    }
    
    public static function boss(){
        $boos = \backend\models\Fish::find()->where(['fishtype'=>5])->asArray()->all();
        $new_boos= ArrayHelper::map($boos,'id','name');
        return $new_boos;
    }
    
    
}