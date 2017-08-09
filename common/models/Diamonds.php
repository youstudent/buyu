<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%diamonds}}".
 *
 * @property integer $id
 * @property integer $need_diamond
 * @property string $content
 * @property integer $updated_at
 */
class Diamonds extends \yii\db\ActiveRecord
{
    public $contents;
    public static $give;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%diamonds}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['need_diamond'],'unique'],
            [['need_diamond'],'required'],
            [['id', 'need_diamond', 'updated_at'], 'integer'],
            [['need_diamond'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'数量必须大于0'],
            [['content','contents'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'need_diamond' => '钻石个数等级',
            'content' => '礼包',
            'updated_at' => '修改时间',
        ];
    }
    
    
    
    
    /**
     *    初始化游戏服务端 钻石等级数据
     */
    public static function GetDiamonds(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getExchangeGold';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        }
        $new = $d[0];
        Diamonds::deleteAll();
        $model =  new Diamonds();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->content=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            $model->need_diamond =$attributes->needDiamond;
            $model->updated_at =time();  //  同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
    
    /**
     * 添加 公告
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
            $pays['needDiamond']=$this->need_diamond;
            if ($this->contents){
                foreach ($this->contents as $key => $value) {
                    if (in_array($key,$datas)) {
                        if ($value<0 || $value==null || !is_numeric($value)){
                            return $this->addError('content','数量无效');
                        }
                        $send[$key] = $value;
                    }
                    if (is_numeric($key)) {
                        if ($value<0 || $value==null || !is_numeric($value)){
                            return $this->addError('content','数量无效');
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
                $pays['send']=$sends;
            }else{
                $pays['send']=$send;
            }
            
            
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/addExchangeGold';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->content=Json::encode($send);
                $this->updated_at        = time();
                $this->save(false);
                return true;
            }
            /*;
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();*/
        }
    }
    
    
    
    public function edit($data = []){
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
            $pays['needDiamond']=$this->need_diamond;
            $pays['id']=$this->id;
            if ($this->contents){
                foreach ($this->contents as $key => $value) {
                    if (in_array($key,$datas)) {
                        if ($value<0 || $value==null || !is_numeric($value)){
                            return $this->addError('content','数量无效');
                        }
                        $send[$key] = $value;
                    }
                    if (is_numeric($key)) {
                        if ($value<0 || $value==null || !is_numeric($value)){
                            return $this->addError('content','数量无效');
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
                $pays['send']=$sends;
            }else{
                $pays['send']=$send;
            }
    
            //$pays['send']=$send;
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
           // var_dump($payss);EXIT;
            $url = \Yii::$app->params['Api'].'/gameserver/control/updateExchangeGold';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->content=Json::encode($send);
                $this->updated_at= time();
                $this->save(false);
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
        parent::__construct($config);
    }
}
