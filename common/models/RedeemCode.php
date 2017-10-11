<?php

namespace common\models;

use api\models\Users;
use backend\models\Shop;
use Codeception\Module\REST;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%redeem_code}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $redeem_code
 * @property integer $caeated_at
 * @property integer $end_time
 * @property string $name
 * @property integer $number
 * @property string $description
 * @property string $prize
 * @property integer $give_type_id
 */
class RedeemCode extends Object
{
     public static $add_type=[1=>'一次使用型',2=>'无限制使用型'];
     public static $scope_type=[1=>'普通用户',2=>'VIP用户',3=>'所有用户'];
     public static $give;
     public $give_type;
     public $gold;//金币
     public $diamond;//钻石
     public $fishGold;//宝石
     public $one; //神灯
     public $tow; //锁定
     public $three;  //冻结
     public $four; //核弹
     public $five;  //狂暴
     public $six;  //黑洞
     public static $type=[1=>'普通礼包',2=>'高级礼包'];
     public $time;
    /**
     * 搜索时使用的用于记住筛选
     * @var string
     */
    public $select  = '';
    public $game_id  = '';
    
    /**
     * 搜索时使用的用于记住关键字
     * @var string
     */
    public $keyword = '';
    
    /**
     * 用户充值的金币数量
     * @var string
     */
    public $pay_gold_num = 0;
    
    /**
     * 用户充值类型
     * @var string
     */
    public $pay_gold_config = '';
    
    /**
     * 充值时候的金额
     * @var int
     */
    public $pay_money    = 0;
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $starttime     = '';
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $endtime      = 0;
    
    public $show        = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%redeem_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['type', 'created_at','number','status'], 'integer'],
            [['description'], 'string'],
            [['prize'], 'string', 'max' => 255],
            [['redeem_code', 'name'], 'string','max' => 50],
            [['one','tow','three','four','five','six'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['end_time','start_time','give_type'],'safe'],
            [['select', 'keyword', 'pay_gold_num','pay_gold_config','game_id','time'], 'safe'],
            [['starttime', 'endtime','gold','diamond','fishGold','scope_type','scope_type','show','give_type','name'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '礼包等级',
            'redeem_code' => '兑换码',
            'created_at' => '创建时间',
            'end_time' => '结束时间',
            'name' => '名字',
            'number' => '兑换码数量',
            'description' => '描述',
            'prize' => '奖品内容',
            'give_type_id' => '赠送类型ID',
            'start_time' => '开始时间',
            'give_type'=>'奖品名',
            'gold'=>'金币',
            'one'=>'神灯',
            'diamond'=>'钻石',
            'fishGold'=>'宝石',//鱼币
            'tow'=>'锁定',
            'three'=>'冻结',
            'four'=>'核弹',
            'five'=>'狂暴',
            'six'=>'黑洞',
            'status'=>'状态',
            'scope_type'=>'礼包范围',
            'time'=>'兑换码有效时间'
        ];
    }
    
    
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','created_at',strtotime($this->starttime)])
            ->andWhere(['<=','created_at',strtotime($this->endtime)]);
        
        if (array_key_exists('show',$data)){
            if (!$data['show']==null){
                $model->andWhere(['add_type'=>$data['show']]);
            }
        }
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
        $json='';
        /*foreach($data as &$v){
               $jsons = json_decode($v['prize'],true);
               foreach ($jsons as $key=>$value){
                   if ($key==1){
                      $v['prize']='金币数量为'.$value;
                   }
                   if ($key==2){
                       $v['prize']=+'钻石数量为'.$value;
                   }
               }
        }*/
       // var_dump($data);exit;
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    
    /**
     * 搜索处理数据函数
     * @return array
     */
    private function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'redeem_code'){
                return ['like','redeem_code',$this->keyword];
            }
        }
        return [];
    }
    
    /**
     * 处理数组 [1,2,3]
     * @param $data
     * @return array|string
     */
    private function searchIn($data)
    {
        $in = [];
        foreach ($data as $item)
            $in[] = $item['id'];
        return $in;
    }
    
    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if($this->starttime == '') {
//            $this->starttime = date('Y-m-d H:i:s',strtotime('-1 month'));
            $this->starttime = \Yii::$app->params['startTime'];//"2017-01-01 00:00:00";//date('Y-m-d H:i:s',strtotime('-1 month'));
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * 添加兑换码
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            $vv =[];
            $re = RedeemCode::$give;
            foreach ($data as $key=>$v){
        
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                        if (array_key_exists($k,$re)){
                            if (empty($v2) || !is_numeric($v2) || $v2<=0){
                                return $this->addError('name','奖品数量无效');
                            }
                            $vv[$k]=$v2;
                        }
                    }
                }
        
            }
            if (empty($this->name)){
                $this->addError('name','名字不能为空');
                return false;
            }
            if ($this->number<1 || empty($this->number) || !is_numeric($this->number)){
            $this->addError('number','数量无效');
            return false;
            }
           
            if (empty($vv)){
                $this->addError('give_type','请选择类型');
                return false;
            }
            foreach ($vv as $kk=>$value){
                if (empty($value)){
                    $this->addError('gold','请选择对应类型的数量');
                    return false;
                }
                if (!is_numeric($value)){
                    $this->addError('gold','请输入数字类型');
                    return false;
                }
            }
            
                //截取时间
                $start ='';
                $end = '';
                if ($this->time){
                    $start = substr($this->time,0,19);
                    $end = substr($this->time,22);
                }
                $prize = json_encode($vv);
                for ($i=1;$i<=(int)$this->number;$i++){
                    $model =new RedeemCode();
                    $model->add_type=1;
                    $model->start_time=$start;
                    $model->end_time=$end;
                    $model->scope_type=3;
                    $model->type=$this->type;
                    $model->name=$this->name;
                    $model->redeem_code=$this->getRandChar(12);
                    $model->created_at=time();
                    $model->prize=$prize;
                    $model->status=0;
                    $model->save();
                }
                
               return true;
            
        }
        
    }
    
    
    /**
     * 添加兑换码
     * @param array $data
     * @return bool
     */
    public function one($data = [])
    {
        if($this->load($data) && $this->validate())
        {
             $vv =[];
            $re = RedeemCode::$give;
            foreach ($data as $key=>$v){
                
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                     if (array_key_exists($k,$re)){
                         if (empty($v2) || !is_numeric($v2) || $v2<=0){
                             return $this->addError('name','奖品数量无效');
                         }
                       $vv[$k]=$v2;
                     }
                    }
                }
                
            }
            
            if (empty($vv)){
                $this->addError('give_type','请选择类型');
                return false;
            }
            foreach ($vv as $kk=>$value){
                if (empty($value)){
                    $this->addError('gold','请选择对应类型的数量');
                    return false;
                }
                if (!is_numeric($value)){
                    $this->addError('gold','请输入数字类型');
                    return false;
                }
            }
            //截取时间
            $start ='';
            $end = '';
            if ($this->time){
                $start = substr($this->time,0,19);
                $end = substr($this->time,22);
            }
            $prize = json_encode($vv);
                $this->add_type=2;
                $this->redeem_code=$this->getRandChar(12);
                $this->created_at=time();
                $this->start_time=$start;
                $this->end_time=$end;
                $this->prize=$prize;
                $this->status=2;
                return $this->save();
        }
        //return true;
        
    }
    
    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $max = strlen($strPol)-1;
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        
        return $str;
    }
    
    //验证验证码
    public function check($data)
    {
        if ($this->load($data, '')) {
            //验证兑换码的有效性
            $result = RedeemCode::find()->where(['redeem_code'=>"$this->redeem_code"])->one();
            if ($result === null || $result === false || $result->status == 1) {
                $this->addError('message', '验证码无效');
                return false;
            }
            //判断是否在兑换时间内
            $time = time();
            if ($time < strtotime($result->start_time) || $time > strtotime($result->end_time)) {
                $this->addError('message', '兑换不在兑换时间范围内');
                return false;
            }
            //到兑换记录中查询该用户是否兑换过该兑换码
            $re = RedeemRecord::findOne(['redeem_code' => $this->redeem_code, 'game_id' => $this->game_id]);
            if ($re) {
                $this->addError('message', '该兑换码你已兑换');
                return false;
            }
            if ($result->status==0){
                $result->status=1;
                $result->save(false);
            }
            if ($results= Users::findOne(['game_id'=>$this->game_id])){
                $model =new RedeemRecord();
                $model->uid=$results->id;//用户表的自增ID
                $model->game_id=$this->game_id;//用户表的自增ID
                $model->redeem_code=$this->redeem_code;//用户表的自增ID
                $model->uid=$results->id;//用户表的自增ID
                $model->nickname=$results->nickname;
                $model->created_at=time();
                $model->status=1;
                $model->save(false);
            }
           
            //去出兑换的奖品   将json格式数据转换成数组
            $Json = json_decode($result->prize);
            $data = [];
            $datas = [];
            //循环数组  将奖品道具和和金币钻石宝石分开
            $tools = [];
            $i = 0;
            $tool = [];
            foreach ($Json as $k => $value) {
                if (is_numeric($k)) {
                    $datas[$k] = $value;
                    $tool['toolId'] = $k;
                    $tool['toolNum'] = $value;
                    $tools[$i] = $tool;
                    $i++;
                } else {
                    $data[$k] = $value;
                }
            
            }
            //组装 游戏服务端需要的数据  格式
            $data['scopeType'] = $result->scope_type;
            $data['tools'] = $tools;
            return $data;
        }
        return false;
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
    
    /*public static function setShop(){
        //查询 道具列表中的数据
        $data  = Shop::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data,'id','name');
        //自定义 赠送类型
        $datas = ['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
        //将数据合并 赋值给数组
        self::$give= ArrayHelper::merge($datas,$new_data);
    }*/
    
    
    // 修改兑换码
    public function edit($data=[]){
        if($this->load($data) && $this->validate()){
            $vv =[];
            $re = RedeemCode::$give;
            foreach ($data as $key=>$v){
        
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                        if (array_key_exists($k,$re)){
                            if (empty($v2) || !is_numeric($v2) || $v2<=0){
                                return $this->addError('name','奖品数量无效');
                            }
                            $vv[$k]=$v2;
                        }
                    }
                }
        
            }
    
            if (empty($vv)){
                $this->addError('give_type','请选择类型');
                return false;
            }
            foreach ($vv as $kk=>$value){
                if (empty($value)){
                    $this->addError('give_type','请选择对应类型的数量');
                    return false;
                }
                if (!is_numeric($value)){
                    $this->addError('give_type','请输入数字类型');
                    return false;
                }
            }
            //截取时间
            $start ='';
            $end = '';
            if ($this->time){
                $start = substr($this->time,0,19);
                $end = substr($this->time,22);
            }
            $prize = json_encode($vv);
            $this->start_time=$start;
            $this->end_time=$end;
            $this->prize=$prize;
            return $this->save();
        }
    }
    
}
