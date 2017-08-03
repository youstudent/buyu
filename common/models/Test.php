<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:08
 */

namespace common\models;


use yii\base\Model;


class Test extends Model
{
    
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
                        $send[$K]=$v;
                    }
                    if (is_numeric($K)){
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