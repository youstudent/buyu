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
            [['owenerid', 'createtime', 'maxmenber'], 'integer'],
            [['name', 'realname', 'bankcard', 'bank', 'idcard', 'phone', 'notice','password'], 'string', 'max' => 255],
            [['notice'],'required','on'=>'edit'],
            [['pay_gold_config','pay_gold'],'safe'],
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
            'fishGold' => '鱼币',
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
        $model = self::find()->where($this->searchWhereLike())
            ->andWhere(['>=','createtime',strtotime($this->starttime)])
            ->andWhere(['<=','createtime',strtotime($this->endtime)]);;
        
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
                return ['like','identity',$this->keyword];
            else
                return ['or',['name'=>$this->keyword],['like','phone',$this->keyword],['like','identity',$this->keyword]];
        }
        return [];
    }
    
    /**
     * 创建家族,创建玩家
     */
    public function add($data = []){
        $this->scenario='add';
        if ($this->load($data) && $this->validate()){
           $transaction  = \Yii::$app->commondb->beginTransaction();
            try {
                $player = new Player();
                $player->familyowner=1;
                $player->uid=$this->phone;
                $player->name=$this->realname;
                $player->pwd=$this->password;
                $player->onlinetime=0;
                $player->createdtime=date('Y-m-d H:i:s');
                if ($player->save()==false){
                    throw  new Exception('创建文件失败');
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
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
    
    /**
     *  修改家族信息
     */
     public function edit($data = []){
         if ($this->load($data) && $this->validate()){
             $transaction  = \Yii::$app->commondb->beginTransaction();
             try{
                 $agency = Agency::findOne(['family_id'=>$this->id]);
                 if ($agency==false || $agency== null){
                     return $this->addError('id','族长未找到');
                 }
                 $player=  Player::findOne(['id'=>$this->owenerid]);
                 if ($player ==false || $player == null){
                     return $this->addError('id','玩家为找到');
                 }
                 if ($this->password){
                     $agency->password=$this->password;
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
                }else{
                   $player->diamond=($player->diamond+$this->pay_gold);
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
                }else{
                    if ($player->diamond<$this->pay_gold){
                        return $this->addError('pay_gold','玩家钻石不足');
                    }
                    $player->diamond=($player->diamond-$this->pay_gold);
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
    
}
