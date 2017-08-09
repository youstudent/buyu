<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:08
 */

namespace common\models;


use yii\db\ActiveRecord;


class Test extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db;  // 使用名为 "secondDb" 的应用组件  重新定义主键
    }
    
    public static function tableName()
    {
        return 'player';
    }
    
    /**
     * @param $data
     * @return array
     *  封装礼包
     */
    public static function set($data){
        $send=[];
        if (empty($data)){
            return $send;
        }
        $datas=['gold','diamond','fishGold'];
        
        $tools = [];
        $i = 0;
        $tool = [];
        foreach ($data as $K=>$v){
                    if (in_array($K,$datas)){
                        if ($v<0 || $v==null || !is_numeric($v)){
                            return false;
                        }
                        $send[$K]=$v;
                    }
                    if (is_numeric($K)){
                        if ($v<0 || $v==null || !is_numeric($v)){
                            return false;
                           // return ['code'=>0,'message'=>'数量无效'];
                        }
                        $tool['toolId']=$K;
                        $tool['toolNum']=$v;
                        $tools[$i]=$tool;
                        $i++;
                    }
            
        }
        if (!empty($tools)){
            $send['tools']=$tools;
        }
        $sends=['gold'=>0,'diamond'=>0,'fishGold'=>0];
        if (empty($send)){
            $content['send']=$sends;
        }else{
            $content['send']=$send;
        }
        return $content;
        
    }
    
    /**
     * @param $send
     * 解析礼包
     */
    public static function get($send){
        $re = DayTask::$give;
        $data=[];
        foreach ($send as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$key]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$v['toolId']]=$v['toolNum'];
                    }
                }
            }
        
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $datas=[];
        $datas['type']=$type;
        $datas['data']=$data;
        return $datas;
    }
    
}