<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;


use common\models\DayTask;
use common\services\Request;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OneCannonForm extends Model
{
    public static $give;
    public $type;
    public $id;
    public $num=0;
    public $typeId;
    public $gives;
    public $enable;
    public $lost=0;
    public $get=0;
    public $type1;
    public $number=0;
    public $out_gold;
    public $time=0;
    public static $enables=[1=>'开启',0=>'关闭'];
    public static $types=[2=>'宝石',0=>'金币'];
    public static $pay_out=[0=>'充值',1=>'消耗'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num','enable','lost','get','number','out_gold','time'],'integer'],
            [['give','type','id','typeId','gives','type1'],'safe'],
            //[['time'],'unique'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'give' => '礼包',
            'type' => '奖励',
            'num' => '单条鱼获得分数',
            'get' => '捕杀货币',
            'lost' => '消耗货币',
            'enable' => '状态',
            'type1' => '货币类型',
            'number' => '钻石数量',
            'time' => '在线分钟',
            'gives' => '礼包',
        ];
    }
    
    
    
    /**
     * 初始化赠送礼包配置
     */
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
        parent::__construct($config);
    }
    
    
    /**
     * @param array $data
     * @return bool
     *  修改每日任务
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            if ($this->typeId == 5){
                if (empty($this->get) || empty($this->lost)){
                    return false;
                }
                $content['type']=$this->type1;
                $content['get']=$this->get;
                $content['lost']=$this->lost;
            }elseif ($this->typeId ==7){
                $content['type']=$this->type1;
                $content['num']=$this->number;
            }elseif ($this->typeId == 9){
                $content['num']=$this->lost;
                $content['type']=$this->type1;
            }elseif ($this->typeId == 10){
                $content['num']=($this->time*1000)*60;
            }else{
                $content['type']=$this->type1;
                $content['num']=$this->num;
            }
            
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $datas=['gold','diamond','fishGold'];
            if ($this->type){
            foreach ($this->type as $key => $value) {
                if (in_array($key,$datas)) {
                    if ($value<0 || $value==null || !is_numeric($value)){
                        return $this->addError('gives','奖品数量无效');
                    }
                    $send[$key] = $value;
                }
                if (is_numeric($key)) {
                    if ($value<0 || $value==null || !is_numeric($value)){
                        return $this->addError('gives','奖品数量无效');
                    }
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
            $sends=['gold'=>0,'diamond'=>0,'fishGold'=>0];
            if (empty($send)){
                $content['send']=$sends;
            }else{
                $content['send']=$send;
            }
            $model = Everydaytask::findOne(['id'=>$this->id]);
            $model->enable=$this->enable;
            $model->content=Json::encode($content);
            return $model->save();
            
        }
    }
    
    
    public function addcannon($data = []){
        if($this->load($data) && $this->validate())
        {
            $name = '弹无虚发';
            if ($this->typeId == 6){
                if (empty($this->num)){
                    $this->addError('num','分数不能为空');
                    return false;
                }
                $content['num']=$this->num;
                $content['type']=$this->type1;
                $name='惊天一炮';
            }elseif ($this->typeId == 7){
                if (empty($this->number)){
                    $this->addError('number','钻石不能为空');
                    return false;
                }
                $name='挥金如土';
                $content['type']=$this->type1;
                $content['num']=$this->number;
            }elseif ($this->typeId==9){
                $name='决战深海';
                if (empty($this->lost)){
                    $this->addError('lost','消耗数量不能为空');
                    return false;
                }
                $content['num']=$this->lost;
                $content['type']=$this->type1;
            }elseif ($this->typeId == 10){
                if (empty($this->time)){
                    $this->addError('time','在线时间不能为空');
                    return false;
                }
                $name='持之以恒';
                $content['num']=($this->time*1000)*60;
            }else{
                if ($this->get<0 || $this->lost<0){
                    $this->addError('get','数量不能小于0');
                    return  false;
                }
                $content['type']=$this->type1;
                $content['get']=$this->get;
                $content['lost']=$this->lost;
            }
            
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $datas=['gold','diamond','fishGold'];
            if ($this->type){
            foreach ($this->type as $key => $value) {
                if (in_array($key,$datas)) {
                    if ($value<0 || $value==null || !is_numeric($value)){
                        return $this->addError('gives','奖品数量无效');
                    }
                    $send[$key] = $value;
                }
                if (is_numeric($key)) {
                    if ($value<0 || $value==null || !is_numeric($value)){
                        return $this->addError('gives','奖品数量无效');
                    }
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
            $sends=['gold'=>0,'diamond'=>0,'fishGold'=>0];
            if (empty($send)){
                $content['send']=$sends;
            }else{
                $content['send']=$send;
            }
    
            $model = new Everydaytask();
            $model->taskname=$name;
            $model->content=Json::encode($content);
            $model->enable=$this->enable;
            $model->typeId=$this->typeId;
            return $model->save();
           
        }
        
    }
    
    
    /**
     * 格式化弹无须发
     */
    public static  function getname($data){
       $json = Json::decode($data,true);
       $type = $json['type'];
       $typename = self::$types[$type];
       $lost = $json['lost'];
       $get = $json['get'];
       return '消耗'.$lost.$typename.':'.'捕杀'.$get.$typename.'的鱼';
    }
    
    
    /**
     * 惊天一炮
     *
     */
    public static function getOne($data){
        $json = Json::decode($data,true);
        $num = $json['num'];
        $type = $json['type'];
        $new = static::$types[$type];
        return '单条鱼获得分数达到'.$num.$new;
    }
    
    /**
     *  汇金入土
     */
    public static function getWaste($data){
        $json = Json::decode($data,true);
        $num = $json['num'];
        $type= $json['type'];
        $new = static::$pay_out[$type];
        return $new.$num.'钻石';
        
    }
    
    
    /**
     *  决战深海
     */
    public static function getDeep($data){
        $json = Json::decode($data,true);
        $num = $json['num'];
        $type= $json['type'];
        $new = static::$types[$type];
        return '每日消耗'.$num.$new;
        
    }
    
    /**
     *  持之以恒
     */
    public static function getGame($data){
        $json = Json::decode($data,true);
        $num = $json['num'];
        $new = ($num/1000)/60;
        return '每日在线:'.$new.'分钟';
        
    }
    
}