<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%currency_pay}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $give_num
 * @property integer $number
 * @property integer $money
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CurrencyPay extends Object
{
    public $type;
    public static $get_type=[1=>'金币',2=>'钻石'];
    public static $give;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'manage_id', 'created_at', 'updated_at','fold'], 'integer'],
            [['money'],'required'],
            [['fold'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'倍数必须大于0'],
            [['manage_name'], 'string', 'max' => 20],
            [['give_prize'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'give_prize' => '礼包类型',
            'money' => '人民币',
            'manage_id' => 'Manage ID',
            'manage_name' => 'Manage Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'fold'=>'倍数',
            'type'=>'赠送类型'
        ];
    }
    
    
    /**
     * 添加充值货币
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            /**
             * pays": [
            {
            "id": 1,
            "money": 1,
            "diamond": 0,
            "firstDouble": 1,
            "send": {
            "sendDiamond": 1,
            "sendGold": 1,
            "sendFishGold": 1,
            "tools": [
            {
            "toolId": 1,
            "toolNum": 1
            },
            {
            "toolId": 2,
            "toolNum": 2
            }
            ]
            }
            },
             */
            
            
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['money']=$this->money;
            $pays['firstDouble']=$this->fold;
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
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
            //$pays['send']=$send;
            /**
             * 请求服务器地址 充值商城添加
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/addPay';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->give_prize=Json::encode($send);
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->created_at         = time();
                $this->save(false);
                return true;
            }
            
        }
    }
    
    //充值货币修改
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
            $pays['money']=$this->money;
            $pays['firstDouble']=$this->fold;
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
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/updatePay';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->give_prize=Json::encode($send);
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->updated_at         = time();
                $this->save(false);
                return true;
            }
            
        }
    }
    
    
    //请求游戏服务器  货币
    public static function GetPay(){
        $url = Yii::$app->params['Api'].'/gameserver/control/gettools';
        $data = Request::request_post($url,['time'=>time()]);
        if ($data['code']==1){
            /*$d=[];
            foreach ($data as $key=>$v){
                if (is_object($v)){
                    $d[]=$v;
                }
            }
            $new = $d[0]->tools;
            foreach ($new as &$e){
                $e->toolName;
            }*/
    
            /*foreach ($new as $K=>$value){
                $model = new Shop();
                $model->save($value);
            }*/
           // Shop::deleteAll();
            $model =  new CurrencyPay();
            foreach($new as $K=>$attributes)
            {
                $model->id=$attributes->toolId;
                $model->name =$attributes->toolName;
                $model->number =1;
                $model->toolDescript =$attributes->toolDescript;
                $model->jewel_number =$attributes->unitPrice;
                $model->level =$attributes->minVip;
                $_model = clone $model;
                $_model->setAttributes($attributes);
                $_model->save(false);
            }
        }else{
            echo '没有数据';
        }
        
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
     * 同步游戏服务端商城充值
     */
    public static function GetCurrency(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getpayinfo';
        $data = \common\services\Request::request_post_raw($url,time());
        $d=[];
        foreach ($data as $key=>$v){
            if (is_object($v)){
                $d[]=$v;
            }
        }
        $new = $d[0]->pays;
        CurrencyPay::deleteAll();
        $model =  new CurrencyPay();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->give_prize=Json::encode($attributes->send);
            $model->id=$attributes->id;
            $model->money =$attributes->money;
            $model->fold =$attributes->firstDouble;
            $model->created_at =time();
            $model->updated_at =time();
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
}
