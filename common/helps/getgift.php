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
     * 处理礼包
     * @param array $data
     * @return bool
     */
    public function disposeGift($data = [])
    {
        $datas['gold'] = '';
        $datas['diamond'] = '';
        $datas['fishgold'] = '';
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
    public static function prize($data){
        if ($data->gold){
            $datas['gold']=$data->gold;
        }
        if ($data->diamond){
            $datas['diamond']=$data->diamond;
        }
        if ($data->fishgold){
            $datas['fishgold']=$data->fishgold;
        }
        if ($data->toolid && $data->toolNum){
           $new_toolid =  explode('_',$data->toolid);
           $new_toolNum =  explode('_',$data->toolNum);
           foreach ($new_toolid as  $value){
               foreach ($new_toolNum as  $values){
                   $datas[$value]=$values;
               }
           }
        }
        if (isset($datas)){
            return $datas;
        }
        return [];
    }
    
}