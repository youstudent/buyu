<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/10/11
 * Time: 15:00
 */

namespace common\helps;


use backend\models\Toolinfo;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class getgift
{
    public $message;
    
    /**
     * 获取礼包
     * @return array
     */
    public static function getGift()
    {
        //查询 道具列表中的数据
        $data = Toolinfo::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data, 'toolid', 'toolname');
        //自定义 赠送类型
        $datas = ['gold' => '金币', 'diamond' => '钻石', 'fishgold' => '宝石'];
        return ArrayHelper::merge($datas, $new_data);
    }
    
    
    /**
     * 获取礼包
     * @return array
     */
    public static function getGifts()
    {
        //查询 道具列表中的数据
        $data = Toolinfo::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data, 'toolid', 'toolname');
        foreach ($new_data as $key=>&$v){
            $new_data[$key.'9'];
        }
        //自定义 赠送类型
        $datas = ['gold9' => '金币', 'diamond9' => '钻石', 'fishgold9' => '宝石'];
        return ArrayHelper::merge($datas, $new_data);
    }
    
    /**
     * 处理礼包
     * @param array $data
     * @return bool
     */
    public function disposeGift($data = [],$joint='')
    {
        $datas['gold'] = 0;
        $datas['diamond'] = 0;
        $datas['fishgold'] = 0;
        if (array_key_exists('gold', $data)) {
            if (!is_numeric($data['gold']) || empty($data['gold']) || $data['gold'] < 1 || !is_int((int)$data['gold'])) {
                $this->message = '数量无效';
                return false;
            }
            $datas['gold'] = $data['gold'];
        }
        if (array_key_exists('diamond', $data)) {
            if (!is_numeric($data['diamond']) || empty($data['diamond']) || $data['diamond'] < 1 || !is_int((int)$data['diamond'])) {
                $this->message = '数量无效';
                return false;
            }
            $datas['diamond'] = $data['diamond'];
        }
        if (array_key_exists('fishgold', $data)) {
            if (!is_numeric($data['fishgold']) || empty($data['fishgold']) || $data['fishgold'] < 1 || !is_int((int)$data['fishgold'])) {
                $this->message = '数量无效';
                return false;
            }
            $datas['fishgold'] = $data['fishgold'];
        }
        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                if (!is_numeric($value) || empty($value) || $value < 1 || !is_int((int)$value)) {
                    $this->message = '数量无效';
                    return false;
                }
                $keys[] = $key;
                $values[] = $value;
            }
        }
        $datas['toolid'] = implode('_', $keys);
        $datas['toolNum'] = implode('_', $values);
        return $datas;
        
    }
    
    /**
     * 处理奖品
     * @param $data
     * @return array
     */
    public static function prize($data,$ji='',$toolid='toolid',$toolNun='toolNum'){
        if ($data[$ji.'gold']){
            $datas['gold']=$data[$ji.'gold'];
        }
        if ($data[$ji.'diamond']){
            $datas['diamond']=$data[$ji.'diamond'];
        }
        if ($data[$ji.'fishgold']){
            $datas['fishgold']=$data[$ji.'fishgold'];
        }
        if ($data[$ji.$toolid] && $data[$ji.$toolNun]){
           $new_toolid =  explode('_',$data[$ji.$toolid]);
           $new_toolNum =  explode('_',$data[$ji.$toolNun]);
           foreach ($new_toolid as  $key=>$value){
                   $datas[$value] = $new_toolNum[$key];
           }
        }
        if (isset($datas)){
            return $datas;
        }
        
        return [];
    }
    
    
    /**
     * 处理奖品
     * @param $data
     * @return array
     */
    public static function prizes($data,$ji='',$toolid='toolid',$toolNun='toolNum'){
        if ($data[$ji.'gold']){
            $datas['gold9']=$data[$ji.'gold'];
        }
        if ($data[$ji.'diamond']){
            $datas['diamond9']=$data[$ji.'diamond'];
        }
        if ($data[$ji.'fishgold']){
            $datas['fishgold9']=$data[$ji.'fishgold'];
        }
        if ($data[$ji.$toolid] && $data[$ji.$toolNun]){
            $new_toolid =  explode('_',$data[$ji.$toolid]);
            $new_toolNum =  explode('_',$data[$ji.$toolNun]);
            foreach ($new_toolid as  $key=>$value){
                $datas[$value] = $new_toolNum[$key];
            }
        }
        if (isset($datas)){
            return $datas;
        }
        
        return [];
    }
    
    /**
     *处理选项和奖品
     * @param $data
     * @return array
     */
    public static function getType($data,$ji='',$toolid='toolid',$toolNun='toolNum'){
        $data = self::prize($data,$ji,$toolid,$toolNun);
        $type=[];
        foreach ($data as $key=>$va){
            $type[]=$key;
        }
        $re['data'] =$data;
        $re['type'] =$type;
        
        return $re;
    }
    /**
     *处理选项和奖品
     * @param $data
     * @return array
     */
    public static function getTypes($data,$ji='',$toolid='toolid',$toolNun='toolNum'){
        $data = self::prizes($data,$ji,$toolid,$toolNun);
        $type=[];
        foreach ($data as $key=>$va){
            $type[]=$key;
        }
        $re['data'] =$data;
        $re['type'] =$type;
        
        return $re;
    }
    
}