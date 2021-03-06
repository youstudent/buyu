<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%battery}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $multiple
 * @property integer $number
 * @property integer $give_gold_num
 * @property integer $updated_at
 * @property integer $manage_id
 * @property string $manage_name
 */
class Battery extends Object
{
    public $type;  //保存礼品类型
    public static $give;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['multiple'],'unique'],
            [['number','multiple'],'required'],
            [['multiple', 'number','updated_at', 'manage_id'], 'integer'],
            [['number','multiple'],'match','pattern'=>'/^$|^\+?[0-9]\d*$/','message'=>'数量不能是负数'],
            [['name', 'manage_name'], 'string', 'max' => 20],
            [['type','give_gold_num'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名字',
            'multiple' => '炮台倍数',
            'number' => '钻石数量',
            'give_gold_num' => '礼包类型',
            'updated_at' => '修改时间',
            'manage_id' => '修改人ID',
            'manage_name' => '修改人',
            'type' => '赠送类型',
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
     *    初始化游戏服务端 炮台倍数
     */
    public static function GetBattery(){
        $url = \Yii::$app->params['Api'].'/control/getbatterypower';
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
            $model->number =$attributes->needdiamond;   //钻石数量
            $model->multiple =$attributes->power;  //炮台倍数
            $model->updated_at =time();  //同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
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
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['number']=$this->number;
            //$pays['name']=$this->name;
            $pays['multiple']=$this->multiple;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_gold_num','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_gold_num','奖品数量无效');
                            }
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
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addbatterypower';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->give_gold_num=Json::encode($send);
                $this->updated_at= time();
                $this->save(false);
                return true;
            }
            /*
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();*/
        }
    }
    
    
    /**
     * 修改炮台倍数
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
            $pays['number']=$this->number;
            $pays['multiple']=$this->multiple;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_gold_num','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_gold_num','奖品数量无效');
                            }
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
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updatebatterypower';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->give_gold_num=Json::encode($send);
                $this->updated_at=time();
                $this->save(false);
                return true;
            }
            
            /*$this->give_gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);*/
        }
    }
}
