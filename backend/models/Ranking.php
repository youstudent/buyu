<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/8/3
 * Time: 14:58
 */

namespace backend\models;


use common\services\Request;
use yii\base\Model;
use yii\helpers\Json;

class Ranking extends Model
{
    public static $option = [''=>'总榜','北京市'=>'北京市','天津市'=>'天津市','河北省'=>'河北省',
                            '山西省'=>'山西省','内蒙古自治区'=>'内蒙古自治区','辽宁省'=>'辽宁省','吉林省'=>'吉林省',
                            '黑龙江省'=>'黑龙江省','上海市'=>'上海市','江苏省'=>'江苏省','浙江省'=>'浙江省',
                            '安徽省'=>'安徽省','福建省'=>'福建省','江西省'=>'江西省','山东省'=>'山东省',
                            '河南省'=>'河南省','湖北省'=>'湖北省','湖南省'=>'湖南省','广东省'=>'广东省',
                            '广西壮族自治区'=>'广西壮族自治区','海南省'=>'海南省','重庆市'=>'重庆市','四川省'=>'四川省','贵州省'=>'贵州省',
                            '云南省'=>'云南省','西藏自治区'=>'西藏自治区','陕西省'=>'陕西省','甘肃省'=>'甘肃省','青海省'=>'青海省','宁夏回族自治区'=>'宁夏回族自治区',
                            '新疆维吾尔自治区'=>'新疆维吾尔自治区','香港特别行政区'=>'香港特别行政区','澳门特别行政区'=>'澳门特别行政区','台湾省'=>'台湾省'];
    
    /*【华北】北京市、天津市、河北省、山西省、内蒙古自治区
    【东北】辽宁省、吉林省、黑龙江省
    【华东】上海市、江苏省、浙江省、安徽省、福建省、江西省、山东省
    【中南】河南省、湖北省、湖南省、广东省、广西壮族自治区、海南省
    【西南】重庆市、四川省、贵州省、云南省、西藏自治区
    【西北】陕西省、甘肃省、青海省、宁夏回族自治区、新疆维吾尔自治区
    【港澳台】香港特别行政区、澳门特别行政区、台湾省*/
    public $province;
    /**
     *    获取排行榜的数据
     */
    public static function GetRanking($type,$province=''){
        $data['type']=$type;
        $data['province']=$province;
        $JS = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/gameserver/control/getList';
        $re = Request::request_post_raw($url,$JS);
        $new_data=$re[0]->players;
        return $new_data;
    }
    
    
    public function set($province){
        $this->province=$province;
        return $this->province;
    }
    
}