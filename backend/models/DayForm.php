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

class DayForm extends Model
{
    public static $give;
    public $type;
    public $gives;
    public $gold;
    public static $option= [0=>'金币',1=>'钻石',2=>'宝石'];
    public $num=0;
    public $typeId;
    public $id;
    public $enable;
    public static $enables=[1=>'开启',0=>'关闭'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num'],'required'],
            [['num'],'integer'],
            [['num'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'数量必须大于0'],
            [['type','num','gold','typeId','id','enable'],'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'num' => '获得数量',
            'gold' => '货币类型',
            'gives' => '礼包',
            'enable'=>'状态'
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
            $content['type']=$this->gold;
            $content['num']=$this->num;
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
    
    
    public function add($data = []){
        if($this->load($data) && $this->validate()) {
            $content['type']=$this->gold;
            $content['num']=$this->num;
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
            $model->taskname ='日进斗金';
            $model->content=Json::encode($content);
            $model->enable=1;
            $model->typeId=$this->typeId;
            return $model->save();
        }
    }
    
    
    public static function  getgold($data){
        $JSON = json_decode($data,true);
        $type =$JSON['type'];
        $num =$JSON['num'];
        $gold = self::$option[$type];
        return '每日获得'.$num.$gold;
        
      
    }
    
}