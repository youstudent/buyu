<?php

namespace common\models;

use backend\models\Fish;
use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%day_task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $fish_number
 * @property string $package
 * @property integer $updated_at
 */
class DayTask extends Object
{
    public static $give;
    public static $fishing;
    public static $fishings;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%day_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'updated_at','status'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['package','fish_number'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '任务名',
            'fish_number' => '任务鱼',
            'package' => '赠送礼包',
            'updated_at' => '更新时间',
            'status' => '状态',
        ];
    }
    
    
    // 创建模型自动设置赠送礼品类型
    public function __construct(array $config = [])
    {
        //查询 道具列表中的数据
        $data  = Shop::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data,'id','name');
        //自定义 赠送类型
        $datas = ['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
        //将数据合并 赋值给数组
        self::$give= ArrayHelper::merge($datas,$new_data);
        
        $data = Fishing::find()->asArray()->all();
        $new_datas = ArrayHelper::map($data,'id','name');
        $re = [0=>'请选择'];
        self::$fishing=ArrayHelper::merge($re,$new_datas);
        parent::__construct($config);
    }
    
    
    public static function setFishing(){
        $fishings =[];
        $i = 'z';
        foreach (self::$give as $K=>$V){
            $fishings[$K.$i]=$V;
        }
        self::$fishings;
        return $fishings;
    }
    
    
    /**
     * @param array $data
     * @return bool
     *  修改每日任务
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            var_dump($data);EXIT;
            /**
             * 接收数据  拼装
             */
           /* $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['id']=$this->id;
            $pays['fishId']=$this->fishing_id;
            $pays['fishNum']=$this->number;
            $pays['rate']=$this->probability;
            $pays['fromFish']=$this->from_fishing;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
                }
            }*/
    
            /**
             *  循环升级礼包
             */
            $levelUp=[];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $datas=['gold','diamond','fishGold'];
            if (array_key_exists('package',$update= $data['DayTask'])){
                $update= $data['DayTask']['package'];
                $updates =[];
                foreach ($update as $A=>$B){
                    if (!is_numeric($A)){
                      $updates[str_replace('z',"",$A)]=$B;
                    }
                }
                foreach ($updates as $key => $value) {
                    if (in_array($key,$datas)) {
                        $levelUp[$key] = $value;
                    }
                    if (is_numeric($key)) {
                        $tool['toolId'] = $key;
                        $tool['toolNum'] = $value;
                        $tools[$i] = $tool;
                        $i++;
                    }
                }
            }
            
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            $levelUp['tools']=$tools;
            var_dump($levelUp);EXIT;
            
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updateFishTask';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                //SignBoard::GetSign();
                /*$this->give_number=Json::encode($send);
                $this->updated_at        = time();
                $this->save(false);*/
                return true;
            }
            
        }
    }
    
    
    /**
     *  获取游戏服务端的列表数据
     */
    public static function GetDay(){
        $url = \Yii::$app->params['Api'].'/control/getEveryDayTask';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
       
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        }
        $new = $d[0];
        DayTask::deleteAll();
        $model =  new DayTask();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->content=$attributes->content;  //赠送礼包
            $model->id=$attributes->id;
            $model->status=$attributes->enable;  //是否开启任务
            $model->type_id=$attributes->typeId;
            $model->description=$attributes->description;
            $model->name =$attributes->taskName;  // 名字
            $model->updated_at =time();  //同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
        
    }
    
    /**
     *  提取鱼名字 和击杀数量
     */
    public static function fishing($data){
       $JSON = json_decode($data,true);
       $fishing_id =$JSON['fishId'];
       $num =$JSON['num'];
       $row = Fish::findOne(['id'=>$fishing_id]);
       if ($row){
           $name =$row->name;
           return '击杀'.$name.$num.'条';
       }
       return '';
    }
    
    
    public static function getFishingType($data){
        $JSON = json_decode($data,true);
        $fishing_id =$JSON['fishId'];
        $re =  Fish::findOne(['id'=>$fishing_id]);
        if ($re){
            if ($re->fishtype ==1){
               return '小鱼';
            }
            if ($re->fishtype ==2){
                return '中鱼';
            }
            if ($re->fishtype ==3){
                return '大鱼';
            }
            if ($re->fishtype ==4){
                return '金鱼';
            }
            if ($re->fishtype ==5){
                return 'BOOS';
            }
        }
        return '';
    }
    
    /**
     *  基础任务详情
     */
    public static function getBasics($data){
        $JSON = json_decode($data,true);
        if (array_key_exists('num',$JSON)){
            return $JSON['num'].'次';
        }
            return '不存在次数';
    }
}
