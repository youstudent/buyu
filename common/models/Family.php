<?php

namespace common\models;

use backend\models\Agency;
use backend\models\AgencyPay;
use Codeception\Module\REST;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;

/**
 * This is the model class for table "family".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ownerid
 * @property string $cretetime
 * @property string $realname
 * @property string $bankcard
 * @property string $bank
 * @property string $idcard
 * @property string $phone
 * @property integer $maxmenber
 * @property string $notice
 */
class Family extends \yii\db\ActiveRecord
{
    public $gold;
    public $diamond;
    public $fishGold;
    
    public $password; //用于保存密码
    
    /**
     * 搜索时使用的用于记住筛选
     * @var string
     */
    public $select  = '';
    
    /**
     * 搜索时使用的用于记住关键字
     * @var string
     */
    public $keyword = '';
    
    /**
     * 充值类型
     * @var string
     */
    public $pay_gold_config = '';
    
    /**
     * 所有金币数量
     * @var array
     */
    public $goldArr = [];
    
    /**
     * 账号状态筛选功能
     * @var int
     */
    public $searchstatus = '' ;
    
    /**
     * 用户推荐码
     * @var string
     */
    public $recode       = '';
    
    /**
     * 充值金额
     * @var int
     */
    public $pay_gold      = 0;
    
    /**
     * 扣除金币
     * @var int
     */
    public $deduct_gold   = 0;
    
    /**
     * 体现备注
     * @var string
     */
    public $deduct_notes       = 0;
    
    /**
     * 体现备注
     * @var string
     */
    public $deduct_money       = '';
    
    /**
     * 充值时收的人民币
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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'family';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->commondb;
       // return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['pay_gold','match','pattern'=>'/^\+?[1-9][0-9]*$/','on'=>'pay'],
            [['name','realname', 'bankcard', 'bank', 'idcard', 'phone', 'maxmenber','password'], 'required','on'=>'add'],
            [['owenerid', 'createtime', 'maxmenber','bankcard','idcard'], 'integer'],
            [['name', 'realname', 'bankcard', 'bank', 'idcard', 'phone', 'notice','password'], 'string', 'max' => 255],
            [['notice'],'required','on'=>'edit'],
            [['pay_gold_config','pay_gold','password','searchstatus'],'safe'],
            [['phone'],'match','pattern'=>'/^((13[0-9])|(15[^4])|(18[0,2,3,5-9])|(17[0-8])|(147))\\d{8}$/'],
            [['select','keyword'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '家族名字',
            'owenerid' => '玩家ID',
            'createtime' => '创建时间',
            'realname' => '银行卡名字',
            'bankcard' => '卡号',
            'bank' => '开户行',
            'idcard' => '身份证',
            'phone' => '手机号',
            'maxmenber' => '家族上线人数',
            'notice' => '公告',
            'password' => '密码',
            'gold' => '金币',
            'diamond' => '钻石',
            'fishGold' => '宝石',
            'pay_gold_config' => '类型',
            'pay_gold' => '数量',
        ];
    }
    
    /**
     * 搜索功能
     * @param $data
     * @return array
     */
    public function search($data)
    {
        $this->load($data);
        $this->initTime();
       // var_dump($this->searchstatus);
        $model = self::find()->where($this->searchWhereLike())
            ->andWhere(['>=','createtime',strtotime($this->starttime)])
            ->andWhere(['<=','createtime',strtotime($this->endtime)]);;
        /*if ($this->searchstatus){
        $model->andWhere(['status'=>$this->searchstatus]);
        }*/
        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data = $model->limit($pages->limit)->offset($pages->offset)->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if($this->starttime == '') {
            $this->starttime = \Yii::$app->params['startTime'];
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    
    /**
     * 搜索处理数据函数
     * @return array
     */
    public function searchWhereLike()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'name')
                return ['like','name',$this->keyword];
            elseif ($this->select == 'phone')
                return ['like','phone',$this->keyword];
            elseif($this->select == 'identity')
                return ['like','idcard',$this->keyword];
            else
                return ['or',['name'=>$this->keyword],['like','phone',$this->keyword],['like','idcard',$this->keyword]];
        }
        return [];
    }
    
    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function add($data = []){
        $this->scenario='add';
        if ($this->load($data) && $this->validate()){
           $transaction  = \Yii::$app->commondb->beginTransaction();
            try {
                if (Player::find()->where(['uid'=>$this->phone])->exists()){
                  return $this->addError('phone','手机号已存在');
                }
                $player = new Player();
                $player->familyowner=1;
                $player->id=$this->getRandChar(8);
                $player->uid=$this->phone;
                $player->name=$this->realname;
                $player->pwd=$this->password;
                $player->onlinetime=0;
                $player->head=1;
                $player->sex=1;
                $player->mac=1;
                $player->lastlogintime=0;
                $player->createdtime=date('Y-m-d H:i:s');
                if ($player->save()==false){
                   // var_dump($player->getErrors());exit;
                    throw  new Exception('创建玩家失败');
                }
                $this->owenerid=$player->id;
                $this->createtime=time();
                $this->notice='暂未公告信息';
                if ($this->save() ==false){
                    throw  new \Exception("创建家族失败");
                }
                $agency = new Agency();
                $agency->phone=$this->phone;
                $agency->password=$this->password;
                $agency->status=1;
                $agency->family_id=$this->id;
                $agency->game_id=$player->id;
                $agency->reg_time=time();
                $agency->name=$this->realname;
                if ($agency->save()==false){
                    throw  new Exception('创建代理失败');
                }
                $Familyplayer = new Familyplayer();
                $Familyplayer->playerid=$player->id;
                $Familyplayer->familyid=$this->id;
                $Familyplayer->status=1;
                $Familyplayer->position=9;
                if (!$Familyplayer->save()){
                    throw  new Exception('加入家族管理');
                }
                //添加家族动态信息
                $Familyrecord = new Familyrecord();
                $Familyrecord->familyid =$this->id;
                $Familyrecord->playerid=$player->id;
                $Familyrecord->type=99;
                $Familyrecord->gold=0;
                $Familyrecord->diamond=0;
                $Familyrecord->fishgold=0;
                if ($Familyrecord->save() ==false) throw new Exception('创建家族记录');
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
    
    function getRandChar($length){
        $str = null;
        $strPol = "0123456789";
        $max = strlen($strPol)-1;
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        
        return $str;
    }
    
    /**
     *  修改家族信息
     */
     public function edit($data = []){
         if ($this->load($data) && $this->validate()){
             $agency = Agency::findOne(['family_id'=>$this->id]);
             if ($this->password){
                 $agency->password=$this->password;
             }
             $agency->save(false);
             $transaction  = \Yii::$app->commondb->beginTransaction();
             try{
                
                 if ($agency==false || $agency== null){
                     return $this->addError('id','族长未找到');
                 }
                 $player=  Player::findOne(['id'=>$this->owenerid]);
                 if ($player ==false || $player == null){
                     return $this->addError('id','玩家为找到');
                 }
                 if ($this->password){
                     $player->pwd=$this->password;
                 }
                 $agency->phone=$this->phone;
                 $player->uid=$this->phone;
                 if ($agency->save(false)==false){
                     throw  new Exception('修改代理失败');
                 }
                 if ($player->save(false)==false){
                     throw  new Exception('修改玩家失败');
                 }
                 if ($this->save(false)==false){
                     throw  new Exception('修改家族失败');
                 }
                 $transaction->commit();
                 return true;
             }catch (Exception $e){
                 $transaction->rollBack();
                 throw $e;
             }
               
         }
         
     }
    
    
    /**
     *   家族表和玩家建立一对一的关系
     */
    public function getUsers(){
        return $this->hasOne(Player::className(),['id'=>'owenerid']);
    }
    
    
    /**
     * 查询族员人数
     */
    public static function getSon($id){
        $data = Familyplayer::find()->where(['familyid'=>$id,'status'=>1])->count();
        if ($data){
            return $data;
        }
            return 0;
    }
    
    
    public static function getAll($id,$type){
        $data = Familyplayer::find()->select("sum($type)")->where(['familyid'=>$id,'status'=>1])->asArray()->one();
        if ($data){
            return  $data["sum($type)"]?$data["sum($type)"]:0;
        }else{
            return 0;
        }
        
    }
    
    /**
     * 代理商充值
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function pay($data)
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            $transaction  = \Yii::$app->db->beginTransaction();
            try{
                $model = self::findOne($this->id);
                $player = Player::findOne(['id'=>$this->owenerid]);
                if ($player == false || $player ==null){
                    throw  new \Exception("玩家不存在");
                }
                if ($this->pay_gold_config ==1){
                   $player->gold=($player->gold+$this->pay_gold);
                }elseif ($this->pay_gold_config==2){
                    $player->diamond=($player->diamond+$this->pay_gold);
                }else{
                    $player->fishGold=($player->fishGold+$this->pay_gold);
                }
               
                if ($player->save() ==false){
                    throw  new \Exception("充值失败");
                }
                
                $agencyPay = new AgencyPay();
                $agencyPay->agency_id = $model->id;
                $agencyPay->name      = $model->realname;
                $agencyPay->time      = time();
                $agencyPay->gold      = $this->pay_gold;
                $agencyPay->status    = 2;
                $agencyPay->manage_id = \Yii::$app->session->get('manageId');
                $agencyPay->manage_name = \Yii::$app->session->get('manageName');
                $agencyPay->gold_config = $this->pay_gold_config;
                $agencyPay->type ='充值';
                if($agencyPay->save(false)== false) throw new \Exception("agencyPay保存数据失败");
                $transaction->commit();
                return true;
            }catch (\Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
    }
    
    
    /**
     * 代理商扣除
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function out($data)
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            $transaction  = \Yii::$app->db->beginTransaction();
            try{
                $model = self::findOne($this->id);
                $player = Player::findOne(['id'=>$this->owenerid]);
                if ($player == false || $player ==null){
                    throw  new \Exception("玩家不存在");
                }
                if ($this->pay_gold_config ==1){
                    if ($player->gold<$this->pay_gold){
                        return $this->addError('pay_gold','玩家金币不足');
                    }
                    $player->gold=($player->gold-$this->pay_gold);
                }else if ($this->pay_gold_config ==2){
                    if ($player->diamond<$this->pay_gold){
                        return $this->addError('pay_gold','玩家钻石不足');
                    }
                    $player->diamond=($player->diamond-$this->pay_gold);
                }else{
                    if ($player->fishGold<$this->pay_gold){
                        return $this->addError('pay_gold','玩家钻石不足');
                    }
                    $player->fishGold=($player->fishGold-$this->pay_gold);
                }
                
                
                
                if ($player->save() ==false){
                    throw  new \Exception("扣除失败");
                }
                
                $agencyPay = new AgencyPay();
                $agencyPay->agency_id = $model->id;
                $agencyPay->name      = $model->realname;
                $agencyPay->time      = time();
                $agencyPay->gold      = $this->pay_gold;
                $agencyPay->status    = 2;
                $agencyPay->manage_id = \Yii::$app->session->get('manageId');
                $agencyPay->manage_name = \Yii::$app->session->get('manageName');
                $agencyPay->gold_config = $this->pay_gold_config;
                $agencyPay->type ='扣除';
                if($agencyPay->save(false)== false) throw new \Exception("agencyPay保存数据失败");
                $transaction->commit();
                return true;
            }catch (\Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
    }
    
    /**
     *  族长和 登录账号建立一对一的关系
     */
    public function getAgency(){
        
        return $this->hasOne(Agency::className(),['family_id'=>'id']);
    }
    
    
    /**
     *  解散家族
     */
    public function dissolve($data=[]){
        if ($this->load($data) && $this->validate()){
            if ($this->password == null){
                return $this->addError('password','密码不能为空');
            }
            //验证密码是否正确
            $aen = Agency::findOne(['id'=>Yii::$app->session->get('agencyId')]);
            if ($aen->password !=$this->password){
                return $this->addError('password','密码错误');
            }
            //查询族长是否已经申请过
            //$dis = Dissolve::findOne(['family_id'=>Yii::$app->session->get('familyId')]);
            $dis = Dissolve::find()->where(['family_id'=>Yii::$app->session->get('familyId'),'status'=>0])->exists();
            if ($dis){
                return $this->addError('password','你已经提交过申请了,请等待平台审核!!');
            }
            
            //密码通过提出申请
            $dissolve = new Dissolve();
            $dissolve->family_id =$aen->family_id;
            $dissolve->re_name =$aen->name;
            $dissolve->name =$this->name;
            $dissolve->status =0;
            $dissolve->time =time();
            return $dissolve->save();
        }
        
    }
    
}
