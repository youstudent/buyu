<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/10
 * Time: 16:08
 */

namespace common\models;


use common\helps\getgift;
use yii\db\ActiveRecord;


class Test extends ActiveRecord
{
    /**
     * @param $data
     * @return array
     *  封装礼包
     */
    public static function set($data){
        $send=[];
        $e =[];
       $sends=['gold'=>0,'diamond'=>0,'fishGold'=>0];
        if (empty($data)){
        return $e['send']=$sends;
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
        $re = getgift::getGiftss();
        $data=[];
        foreach ($send as $key=>$value){
            if (array_key_exists($key,$re)){
                if ($value>0){
                    $data[$key]=$value;
                }
                
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        if ($v['toolNum']>0){
                            $data[$v['toolId']]=$v['toolNum'];
                        }
                        
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