<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%vip_update}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $number
 * @property integer $give_gold_num
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $updated_at
 * @property string $grade
 */
class VipUpdate extends Object
{
    public $type;   //显示
    public static $give;
    public static $give_day;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_update}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','grade','burst','alms_rate','alms_num'],'required'],
            [['number','alms_rate','alms_num'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['number','manage_id', 'updated_at','alms_rate','alms_num'], 'integer'],
            [['manage_name', 'grade'],'string', 'max' => 20],
            [['give_day','give_upgrade','type'],'safe'],
            [['burst'],'valeburst']
        ];
    }
    
    
    public function valeburst(){
        if ($this->burst<0.01 || $this->burst>100){
            return $this->addError('burst','爆率范围在0.01-100之间');
        }
        
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '每日赠送',
            'number' => '人民币',
            'give_day' => '每日礼包',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
            'grade' => 'Vip等级',
            'burst' => '爆率',
            'give_upgrade' =>'升级赠送',
            'alms_num' =>'增加救济次数',
            'alms_rate' =>'获得救济金比例',
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
        $re =[];
        $i = 9;
        foreach (self::$give as $K=>$V){
            $re[$K.$i]=$V;
         }
         self::$give_day=$re;
        parent::__construct($config);
    }
    
    /**
     *    初始化游戏服务端  vip等级每日福利
     */
    public static function GetVipBenefit(){
        $url = \Yii::$app->params['Api'].'/control/getVIP';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        
        }
        $new = $d[0];
        VipUpdate::deleteAll();
        $model =  new VipUpdate();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->id=$attributes->id;
            $model->give_day=Json::encode($attributes->sign);  //每日礼包
            $model->give_upgrade=Json::encode($attributes->levelUp);  //升级礼包
            $model->number=$attributes->vipEx;  //人民币
            $model->grade =$attributes->vipLevel; //等级
            $model->burst =$attributes->killrate; //等级
            $model->alms_num =$attributes->almsNum; //等级
            $model->alms_rate =$attributes->almsRate; //等级
            $model->updated_at =time(); // 同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
    
    /**
     * 添加 vip等级每日福利
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $levelUp=[]; //升级
            $sign=[];  //每日
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['grade']=$this->grade;  //等级
            $pays['number']=$this->number;   //人民币
            $pays['burst']=$this->burst;   //暴力
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
                $levelUp['tools']=$tools;
            }
            if (!empty($levelUp)){
                $pays['levelUp']=$levelUp;
            }
            if (!empty($sign)){
                $pays['sign']=$sign;
            }
            
            /**
             * 请求服务器地址 VIP升级福利
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addVIP';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }
          //  $this->give_gold_num=Json::encode();
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();
        }
    }
    
    
    /**
     * 修改 vip 升级福利
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate()) {
            if ($this->burst<0 || $this->alms_rate>100 || $this->burst>100 ||$this->alms_rate<0){
                return $this->addError('alms_rate','爆率和救济金领取比例在0-100之间');
            }
            /**
             * 接收数据  拼装
             */
           // $datas = self::$give;
            $datas=['gold','diamond','fishGold'];
            $pays = [];
            $levelUp = []; //升级
            $sign = [];  //每日
            $tools = [];
            $toolss = [];
            $i = 0;
            $tool = [];
            $pays['id'] = $this->id;  //等级
            $pays['vipLevel'] = $this->grade;  //等级
            $pays['vipEx'] = $this->number;   //人民币
            $pays['killRate'] = $this->burst*100;   //暴力
            $pays['almsNum'] = $this->alms_num; //领取次数
            $pays['almsRate'] = $this->alms_rate*100; // 领取比例
    
            /**
             * 解析 升级
             */
            
            if (!empty($data['VipUpdate'])) {
                /**
                 *  循环每日礼包
                 */
                if (array_key_exists('day',$data['VipUpdate'])){
                    $day = $data['VipUpdate']['day'];
                    foreach ($day as $key => $value) {
                        if (in_array($key,$datas)) {
                            if ($value<=0 || $value==null || !is_numeric($value)){
                                return $this->addError('give_upgrade','奖品数量无效');
                            }
                            $sign[$key] = $value;
                        }
                        if (is_numeric($key)) {
                            if ($value<=0 || $value==null || !is_numeric($value)){
                                return $this->addError('give_upgrade','奖品数量无效');
                            }
                            $tool['toolId'] = $key;
                            $tool['toolNum'] = $value;
                            $tools[$i] = $tool;
                            $i++;
                        }
        
                    }
                }
                /**
                 *  循环升级礼包
                 */
                if (array_key_exists('upgrade',$update= $data['VipUpdate'])){
                    $update= $data['VipUpdate']['upgrade'];
                    $updates =[];
                    foreach ($update as $A=>$B){
                        $updates[str_replace(9,"",$A)]=$B;
                    }
                    foreach ($updates as $key => $value) {
                        if (in_array($key,$datas)) {
                            if ($value<=0 || $value==null || !is_numeric($value)){
                                return $this->addError('give_upgrade','奖品数量无效');
                            }
                            $levelUp[$key] = $value;
                        }
                        if (is_numeric($key)) {
                            if ($value<=0 || $value==null || !is_numeric($value)){
                                return $this->addError('give_upgrade','奖品数量无效');
                            }
                            $tool['toolId'] = $key;
                            $tool['toolNum'] = $value;
                            $toolss[$i] = $tool;
                            $i++;
                        }
                    }
                }
                
            }
    
            if (!empty($tools)) {
                $sign['tools'] = $tools;
            }
            if (!empty($toolss)) {
                $levelUp['tools'] = $toolss;
            }
            if (!empty($levelUp)) {
                $pays['levelUp'] = $levelUp;
            }
            if (!empty($sign)) {
                $pays['sign'] = $sign;
            }
      
            $payss = Json::encode($pays);
           // var_dump($payss);exit;
            /**
             *
             * 请求游戏服务端  Vip升级福利
             */
            $url = \Yii::$app->params['Api'].'/control/updateVIP';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->burst= $this->burst*100;
                $this->alms_rate=$this->alms_rate*100;
                $this->give_day=Json::encode($levelUp);
                $this->give_upgrade=Json::encode($sign);
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->updated_at         = time();
                $this->save(false);
                return true;
            }
            
        }
    }
}
