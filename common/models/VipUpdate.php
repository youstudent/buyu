<?php

namespace common\models;

use backend\models\Shop;
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
            [['number','grade'],'required'],
            [['number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['type', 'number','manage_id', 'updated_at'], 'integer'],
            [['manage_name', 'grade'],'string', 'max' => 20],
            [['give_gold_num'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '礼包类型',
            'number' => '所需钻石数量',
            'give_gold_num' => '赠送金币数量',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
            'grade' => 'Vip等级范围',
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
     *    初始化游戏服务端  vip等级每日福利
     */
    public static function GetVipBenefit(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getbatterypower';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_object($v)){
                $d[]=$v;
            }
        }
        $new = $d[0]->pays;
        Battery::deleteAll();
        $model =  new Battery();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->give_gold_num=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            $model->grade =$attributes->power; //等级
            //$model->multiple =$attributes->needdiamond;  //炮台倍数
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
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
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['grade']=$this->number;
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
            $send['tools']=$tools;
            $pays['send']=$send;
            /**
             * 请求服务器地址 VIP升级福利
             */
            /*$payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/getpayinfo';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }*/
            $this->give_gold_num=Json::encode($send);
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
            $pays['grade']=$this->number;
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
            $send['tools']=$tools;
            $pays['send']=$send;
            /**
             * 请求游戏服务端  Vip升级福利
             */
            /*$payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/getpayinfo';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
              return true;
            }*/
            $this->give_gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);
        }
    }
}
