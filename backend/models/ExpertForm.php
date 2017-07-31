<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;


use common\models\Fishing;
use common\services\Request;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ExpertForm extends Model
{
    public static $give;
    public static $fishing;
    public $fishings;
    public $gives;
    public $num;
    public $type;
    public static $boos;
    public $typeId;
    public $id;
    public $enable;
    public static $enables=[0=>'关闭',1=>'开启'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gives','num','fishings','type','typeId','id','enable'],'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gives' => '礼包',
            'fishings' => '选择鱼',
            'num' => '数量',
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
        
        $data = Fishing::find()->asArray()->all();
        $new_datas = ArrayHelper::map($data,'id','name');
        $re = [0=>'请选择'];
        self::$fishing=ArrayHelper::merge($re,$new_datas);
        $boos = Fishing::find()->where(['type'=>5])->asArray()->all();
        $new_boos= ArrayHelper::map($boos,'id','name');
        self::$boos = ArrayHelper::merge($re,$new_boos);
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
            $content['fishId']=$this->fishings;
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
                return true;
            }
        }
            
        
    }
    
    public function add($data = [])
    {
        if($this->load($data) && $this->validate()) {
            $arr = [];
            $content['fishId']=$this->fishings;
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
    
    
    
}