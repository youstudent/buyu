<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;


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
    public static $option= [0=>'金币',1=>'钻石',2=>'鱼币'];
    public $num;
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
            [['type','num','gold','typeId','id','enable'],'safe']
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
            $arr = [];
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
                    $send[$key] = $value;
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
            $content['send']=$send;
            $arr['enable']=$this->enable;
            $arr['id']=$this->id;
            $arr['typeId']=$this->typeId;
            $arr['content']=$content;
            $JS = Json::encode($arr);
            /**
             * 请求游戏服务端   修改数据
             */
            $url = \Yii::$app->params['Api'].'/gameserver/control/updateEveryDayTask';
            $re = Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                //SignBoard::GetSign();
                /*$this->give_number=Json::encode($send);
                $this->updated_at        = time();
                $this->save(false);*/
                return true;
            }
            
        }
    }
    
    
    public function add($data = []){
        if($this->load($data) && $this->validate()) {
            $arr=[];
            $content['type']=$this->gold;
            $content['num']=$this->num;
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $datas=['gold','diamond','fishGold'];
            foreach ($this->type as $key => $value) {
                if (in_array($key,$datas)) {
                    $send[$key] = $value;
                }
                if (is_numeric($key)) {
                    $tool['toolId'] = $key;
                    $tool['toolNum'] = $value;
                    $tools[$i] = $tool;
                    $i++;
                }
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            $content['send']=$send;
            $arr['enable']=1;
            $arr['typeId']=$this->typeId;
            $arr['content']=$content;
            $JS = Json::encode($arr);
            /**
             * 请求游戏服务端   修改数据
             */
            $url = \Yii::$app->params['Api'].'/gameserver/control/addEveryDayTask';
            $re = Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                return true;
            }
    
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