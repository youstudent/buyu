<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%day}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $give_type
 * @property string $day
 * @property integer $gold_num
 * @property integer $jewel_num
 * @property integer $salvo_num
 * @property integer $updated_at
 * @property string $manage_name
 * @property integer $manage_id
 */
class Day extends Object
{
    public $type;
    public static $give;
    public static $get_type=[1=>'首次',2=>'循环'];
    public static $get_give_type=[1=>'金币',2=>'钻石',3=>'礼炮'];
    public static $get_gives_type=[1=>'金币',2=>'钻石',3=>'礼炮',12=>'金币,钻石',13=>'金币,礼炮',123=>'金币,钻石,礼炮',23=>'钻石,礼炮',''=>''];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%day}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jewel_num', 'salvo_num', 'updated_at', 'manage_id'], 'integer'],
            [['day','manage_name'], 'string', 'max' => 20],
            [['jewel_num','salvo_num'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['give_type','type','gold_num'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '赠送类型',
            'give_type' => '赠送炮台',
            'day' => '签到天数',
            'gold_num' => '金币数量',
            'jewel_num' => '钻石数量',
            'salvo_num' => '礼炮数量',
            'updated_at' => '修改时间',
            'manage_name' => '修改人',
            'manage_id' => '修改ID',
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
        parent::__construct($config);
    }
    
    
    /**
     * 修改 每日签到管理
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            /**
             * 接收数据  拼装
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['id']=$this->id;
            if ($this->give_type == 1){
                $pays['batteryId']=1;
            }
            $pays['level']=$this->jewel_num;
            $pays['day']=$this->day;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('type','数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('type','数量无效');
                            }
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
                }
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            };
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updateSign';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->gold_num=Json::encode($send);
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->updated_at         = time();
                $this->save(false);
                return true;
            }
           /* $this->gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);*/
        }
    }
    
    
    /**
     * 添加 炮台倍数
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
             $day =  Day::find()->select(['day'])->where(['jewel_num'=>$this->jewel_num])->orderBy('day DESC')->limit(1)->one();
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $days =1;
            if ($day){
             $days = $day->day+1;
            }
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['day']=$days;
            $pays['level']=$this->jewel_num;
            //$pays['name']=$this->name;
            if ($this->give_type == 1){
            $pays['batteryId']=1;
            }
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('type','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('type','奖品数量无效');
                            }
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
                }
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addSign';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->day=$days;
                $this->gold_num=Json::encode($send);
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->updated_at         = time();
                $this->save(false);
                return true;
            }
            /*$this->give_gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();*/
        }
    }
    
    
    /**
     * 获取游戏服务端,救济金数据
     */
    public static function GetDay(){
        $url = \Yii::$app->params['Api'].'/control/getSign';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
            
        }
        //var_dump($d);EXIT;
        $new = $d[0];
        Day::deleteAll();
        $model =  new Day();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->gold_num=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            if (property_exists($attributes,'batteryId')){
            $model->give_type=1;
            }else{
            $model->give_type=0;
            }
            $model->jewel_num =$attributes->type;   // 类型 首次 还是 循环
            $model->day =$attributes->day;  // 天数
            $model->updated_at =time();  // 同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
}
