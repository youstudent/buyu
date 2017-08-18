<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%sign_board}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $number
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $updated_at
 * @property string $dateil
 */
class SignBoard extends Object
{
    public $type;
    public static $give;
    public static $fishing;
    public static $prize;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sign_board}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','probability','from_fishing'],'required'],
            [['number', 'manage_id', 'updated_at','fishing_id'], 'integer'],
            [['number','probability'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['name'], 'string'],
            [['manage_name'], 'string', 'max' => 20],
            [['give_number','type','from_fishing'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fishing_id' => '鱼名字',
            'number' => '击杀数量',
            'manage_id' => '修改人ID',
            'manage_name' => '修改人',
            'updated_at' => '更新时间',
            'give_number' => '礼包类型',
            'probability' => '出现概率%',
            'name' => '鱼名字',
            'type' => '鱼类型',
            'from_fishing' => '任务鱼',
        ];
    }
    
    
    
    //请求游戏服务器  任务列表
    public static function GetSign(){
        $url = Yii::$app->params['Api'].'/control/getFishTask';
        $data = Request::request_post($url,['time'=>time()]);
       
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        
        }
        $new = $d[0];
        
        /*foreach ($new as $K=>$value){
            $model = new Shop();
            $model->save($value);
        }*/
        SignBoard::deleteAll();
        $model =  new SignBoard();
        foreach($new as $K=>$attributes)
        {
            $model->id =$attributes->id;
            $model->fishing_id =$attributes->fishId;
            $model->give_number =Json::encode($attributes->send);
            $model->number =$attributes->fishNum;
            $model->probability =$attributes->rate;
            $model->from_fishing =Json::encode($attributes->fromFish);
            $model->updated_at =time();
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
    
    
    /**
     * 添加  任务
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            // Notice::findOne(['location'=>$this->location,'status'=>1]);
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['fishId']=$this->fishing_id;
            $pays['fishNum']=$this->number;
            $pays['rate']=$this->probability*100;
            $pays['fromFish']=$this->from_fishing;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_number','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_number','奖品数量无效');
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
            /*  $send['tools']=$tools;
              $pays['send']=$send;*/
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addFishTask';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                SignBoard::GetSign();
               /* $this->from_fishing=Json::encode($this->from_fishing);
                $this->give_number=Json::encode($send);
                $this->updated_at        = time();
                $this->save(false);*/
                return true;
            }
            /*;
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();*/
        }
    }
    
    
    /**
     * @param array $data
     * @return bool
     *  修改 任务
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
            $pays['fishId']=$this->fishing_id;
            $pays['fishNum']=$this->number;
            $pays['rate']=$this->probability*100;
            $pays['fromFish']=$this->from_fishing;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_number','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give_number','奖品数量无效');
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
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updateFishTask';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                SignBoard::GetSign();
                /*$this->give_number=Json::encode($send);
                $this->updated_at        = time();
                $this->save(false);*/
                return true;
            }
            
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
        
        $data = Fishing::find()->asArray()->all();
        $new_datas = ArrayHelper::map($data,'id','name');
       // $re = [0=>'请选择'];
        self::$fishing= $new_datas;
        parent::__construct($config);
    }
    
    public static function GetFishing(){
    
    }
}
