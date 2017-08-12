<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use common\models\GoldConfigObject;
use common\models\OnLine;
use common\models\Player;
use common\models\UsersGoldObject;
use common\models\UsersObject;
use common\services\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\DomCrawler\Field\InputFormField;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\ViewAction;

/**
 * 平台玩家模型类、对外提供
 * Class Userss
 * @package backend\models
 */
class Users extends UsersObject
{

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

    public function rules()
    {
        return [
            [['select','keyword','pay_gold_num','pay_gold_config'],'safe'],
            //['pay_gold_num','integer','on'=>'pay'],
           // ['pay_gold_num','match','pattern'=>'/^\+?[1-9][0-9]*$/','on'=>'pay'],
            ['pay_money','number','on'=>'pay'],
            [['starttime','endtime','detail','type'],'safe'],
        ];
    }


    public function attributeLabels()
    {
        $arr = [
                'pay_gold_num'    =>'数量',
                'pay_money'       =>'人民币',
                'pay_gold_config' =>'充值类型',
                'detail'=>'详情',
                'type'=>'类型'
        ];
        return ArrayHelper::merge(parent::attributeLabels(),$arr);
    }

    /**
     * 用户充值功能
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function pay($data = [])
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            
            /**
             * 查询用户是否存在
             */
            $model = self::findOne($data['id']);
            if($model)
            {
                /**
                 * 请求游戏服务器、并判断返回值进行逻辑处理
                 */
                $config ='';
                if ($this->pay_gold_config=='金币'){
                    $config=1;
                }elseif($this->pay_gold_config=='钻石'){
                    $config=2;
                }else{
                    $config=3;
                }
               // $data = Request::request_post(\Yii::$app->params['Api'].'/gameserver/control/deposit',['game_id'=>$model->game_id,'pay_gold_config'=>$config,'pay_money'=>$this->pay_money]);
                /*if($this->pay_gold_config == '金币'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=chargeCard&uid=".$model->game_id."&card=".$this->pay_gold_num;
                }elseif($this->pay_gold_config == '钻石'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=charge&uid=".$model->game_id."&cash=".$this->pay_gold_num;
                }elseif($this->pay_gold_config == '鱼币'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=charge&uid=".$model->game_id."&cash=".$this->pay_gold_num;
                }
                $data = Request::request_get($url);*/
                $data['code']=1;
                if($data['code'] == 1)
                {
                   
                    /**
                     * 开启数据库的事务操作
                     */
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $test = \common\models\Test::findOne(['id'=>$model->game_id]);
                            $model->gold=$test->gold;
                            $model->gem=$test->fishGold;
                            $model->jewel=$test->diamond;
                            //$model->phone=$v['phone'];
                           // $model->vip_grade=$v['viplevel'];
                            //->reg_time=strtotime($v['createdtime']);
                            // $model->time_day=$v->onlineTime;
                            //$model->time_online=$v->totalOnlineTime;
                            // var_dump($model);EXIT;
                            //var_dump($model);exit;
                            // exit;
        
                           // $model->update(false);

//                        $goldConfig = $model->getGold();
//                        foreach ($goldConfig as $key=>$val){
//
//                        }
                        //人民币
                       // $this->pay_gold_num=$this->pay_money*50;
                        //$data = $model->payGold($this->pay_gold_config,$this->pay_gold_num);
                       /* if ($this->pay_gold_config== '金币'){
                          $model->gold=($model->gold+$this->pay_gold_num);
                        }
                        if ($this->pay_gold_num=='钻石'){
                          $model->jewel=($model->jewel+$this->pay_gold_num);
                        }else{
                          $model->gem=($model->gem+$this->pay_gold_num);  //鱼币
                        }*/
                        
                        if (!$model->save(false))
                            throw new \Exception('save error 101023'); /* 保存失败抛出异常 */

                        /**
                         * 保存用户充值记录
                         */
                        $userModel = new UserPay();
                        $userModel->agency_id   = '1';
                        $userModel->agency_name = '平台';
                        $userModel->user_id     = $model->id;
                        $userModel->game_id     = $model->game_id;
                        $userModel->nickname    = $model->nickname;
                        $userModel->time        = time();
                        $userModel->type ='充值';
                        $userModel->gold        = abs($this->pay_gold_num);
                        $userModel->money       = $this->pay_money;
                        $userModel->status      = 1;
                        //$userModel->detail      = $this->detail;
                        $userModel->gold_config = $this->pay_gold_config;

                        /* 保存失败抛出异常 */
                        if ($userModel->save()) {
                            $transaction->commit();
                            return true;
                        }else{
                            throw new \Exception('save error 101024'.reset($userModel->getFirstErrors()));
                        }

                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }else{
                    return $this->addError('pay',$data['message']);
                }
            }
        }
    }
    
    
    
    /**
     * 用户充值功能
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function out($data = [])
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            
            /**
             * 查询用户是否存在
             */
            $model = self::findOne($data['id']);
            if($model)
            {
                /*if ($this->pay_gold_num ==0){
                    return $this->addError('pay_gold_num',"数量不能为0");
                }
                
                if ($this->pay_gold_num < 0){
                    $gold = UsersGoldObject::find()->Where(['users_id'=>$data['id']])->andWhere(['gold_config'=>$this->pay_gold_config])->one();
                    if ($gold->gold <= abs($this->pay_gold_num)){
                        return $this->addError('pay_gold_num',"玩家.$this->pay_gold_config.不足！");
                    }
                }*/
                $config ='';
                if ($this->pay_gold_config=='金币'){
                    $config=1;
                }elseif($this->pay_gold_config=='钻石'){
                    $config=2;
                }else{
                    $config=3;
                }
                //$data = Request::request_post(\Yii::$app->params['Api'].'/gameserver/control/undeposit',['game_id'=>$model->game_id,'pay_gold_config'=>$config,'pay_money'=>$this->pay_money]);
                //$data = Request::request_post(\Yii::$app->params['ApiUserPay'],['game_id'=>$model->game_id,'pay_gold_config'=>$config,'pay_money'=>$this->pay_money]);
                /**
                 * 请求游戏服务器、并判断返回值进行逻辑处理
                 */
                //$data = Request::request_post(\Yii::$app->params['ApiUserPay'],['game_id'=>$model->game_id,'gold'=>$this->pay_gold_num,'gold_config'=>GoldConfigObject::getNumCodeByName($this->pay_gold_config)]);
                /*if($this->pay_gold_config == '金币'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=chargeCard&uid=".$model->game_id."&card=".$this->pay_gold_num;
                }elseif($this->pay_gold_config == '钻石'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=charge&uid=".$model->game_id."&cash=".$this->pay_gold_num;
                }elseif($this->pay_gold_config == '鱼币'){
                    $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=charge&uid=".$model->game_id."&cash=".$this->pay_gold_num;
                }
                $data = Request::request_get($url);*/
                $data['code']=1;
                if($data['code'] == 1)
                {
                    
                    /**
                     * 开启数据库的事务操作
                     */
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $test = \common\models\Test::findOne(['id'=>$model->game_id]);
                        $model->gold=$test->gold;
                        $model->gem=$test->fishGold;
                        $model->jewel=$test->diamond;
                       // $this->pay_gold_num=$this->pay_money*50;
                        //$data = $model->payGold($this->pay_gold_config,$this->pay_gold_num);
                        /*if ($this->pay_gold_config== '金币'){
                            $model->gold=($model->gold-$this->pay_gold_num);
                        }
                        if ($this->pay_gold_num=='钻石'){
                            $model->jewel=($model->jewel-$this->pay_gold_num);
                        }else{
                            $model->gem=($model->gem-$this->pay_gold_num);  //鱼币
                        }*/
                        if (!$model->save(false))
                            throw new \Exception('save error 101023'); /* 保存失败抛出异常 */

//                        $goldConfig = $model->getGold();
//                        foreach ($goldConfig as $key=>$val){
//
//                        }
                       // $this->pay_gold_num=$this->pay_money*50;
                        //$data = $model->payOut($this->pay_gold_config,$this->pay_gold_num);
                        
                        //if (!$data)
                           // $this->addError('pay_gold_num',"玩家.$this->pay_gold_config.不足");
                           // return false;
                           //throw new \Exception('save error 101023'); /* 保存失败抛出异常 */
                        
                        /**
                         * 保存用户充值记录
                         */
                        $userModel = new UserPay();
                        $userModel->agency_id   = '1';
                        $userModel->agency_name = '平台';
                        $userModel->user_id     = $model->id;
                        $userModel->game_id     = $model->game_id;
                        $userModel->nickname    = $model->nickname;
                        $userModel->time        = time();
                        $userModel->type ='扣除';
                        $userModel->gold        = abs($this->pay_gold_num);
                        $userModel->money       = $this->pay_money;
                        $userModel->status      = 1;
                        $userModel->gold_config = $this->pay_gold_config;
                        
                        /* 保存失败抛出异常 */
                        if ($userModel->save()) {
                            $transaction->commit();
                            return true;
                        }else{
                            throw new \Exception('save error 101024'.reset($userModel->getFirstErrors()));
                        }
                        
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }else{
                    return $this->addError('out',$data['message']);
                }
            }
        }
    }
    /**
     * 搜索并分页显示用户的数据
     * @return array
     */
    public function getList($data = [])
    {
        
        $this->load($data);
        $this->initTime();
        $model   = Player::find()->andWhere($this->searchWhere())
                               ->andWhere(['>=','createdtime',$this->starttime])
                               ->andWhere(['<=','createdtime',$this->endtime]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );

        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('id ASC')->all();
        /*foreach ($data as $key=>$value){
            $data[$key]['gold'] = $value->getGold();
        }*/
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }

    /**
     * 搜索并分页显示用户充值记录
     * @param array $data
     * @return array
     */
    public function getPayLog($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
                ->andWhere(['>=','reg_time',strtotime($this->starttime)])
                ->andWhere(['<=','reg_time',strtotime($this->endtime)]);
        $idArray = $model->asArray()->select('id')->all();
        $model   = UserPay::find()->where(['IN','user_id',$this->searchIn($idArray)])->andWhere(['type'=>'充值']);
        $pages   = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data    = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    /**
     * 搜索并分页显示用户充值记录
     * @param array $data
     * @return array
     */
    public function getPayOut($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','reg_time',strtotime($this->starttime)])
            ->andWhere(['<=','reg_time',strtotime($this->endtime)]);
        $idArray = $model->asArray()->select('id')->all();
        $model   = UserPay::find()->where(['IN','user_id',$this->searchIn($idArray)])->andWhere(['type'=>'扣除']);
        $pages   = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data    = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }

    /**
     * 搜索并分页显示用户消费记录
     * @param array $data
     * @return array
     */
    public function getOutLog($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','reg_time',strtotime($this->starttime)])
            ->andWhere(['<=','reg_time',strtotime($this->endtime)]);
        $idArray = $model->asArray()->select('id')->all();
        $model   = UserOut::find()->where(['IN','user_id',$this->searchIn($idArray)]);
        $pages   = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data    = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }

    /**
     * 搜索并分页显示用户战绩记录
     * @param array $data
     * @return array
     */
    public function getExploits($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','reg_time',strtotime($this->starttime)])
            ->andWhere(['<=','reg_time',strtotime($this->endtime)]);
        $idArray = $model->asArray()->select('id')->all();
        $model   = GameExploits::find()->where(['IN','user_id',$this->searchIn($idArray)]);
        $pages   = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data    = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    /**
     * 搜索并分页显示黑名单列表
     * @param array $data
     * @return array
     */
    public function blacklist($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = Blacklist::find()->andWhere($this->searchWhere());
           // ->andWhere(['>=','reg_time',strtotime($this->starttime)])
           // ->andWhere(['<=','reg_time',strtotime($this->endtime)])->andWhere(['status'=>2]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
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
            
            if ($this->select == 'game_id')
                return ['id'=>$this->keyword];
            elseif ($this->select == 'nickname')
                return ['like','name',$this->keyword];
            else
                return ['or',['id'=>$this->keyword],['like','name',$this->keyword]];
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
     * 处理账号 停封和启用
     */
    public function pass($info)
    {
        $id = '';
        $status = '';
        foreach ($info as $v) {
            $id = $v['id'];
            $status = $v['status'];
        }
        $model = self::findOne(['id' => $id]);
        if ($status == 1) {
            $url = \Yii::$app->params['ApiUserPay'] . "?mod=gm&act=lockAccount&uid=" . $model->game_id;
        }else{
            $url = \Yii::$app->params['ApiUserPay'] . "?mod=gm&act=unLockAccount&uid=" . $model->game_id;
        }
        $data = Request::request_get($url);

        if ($data['code'] == 1) {
            /**
             * 开启数据库的事务操作
             */
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                /**
                 * 保存用户充值记录
                 */
                $model = self::findOne(['id' => $id]);

                if ($model->status == 1) {
                    $model->status = 0;
                } else {
                    $model->status = 1;
                }

                /* 保存失败抛出异常 */
                if ($model->save(false)) {
                    $transaction->commit();
                    return true;
                } else {
                    throw new \Exception('save error 101024' . reset($model->getFirstErrors()));
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->addError('lock','通信错误');
        }
    }
    
    //修改时间
     public function set($data){
        $model  = Users::findOne(['game_id'=>$data]);
        return $model;
    }
    
    
    public function time($data ='')
    {
        if ($this->load($data, '') && $this->validate()) {
        
        }
    }
    
    
    //取消封停
    public function ban($id){
        $datas['playerId']=$id;
        $result = Request::request_post(\Yii::$app->params['Api'].'/gameserver/control/unban',$datas);
        if($result){
             return UsersTime::deleteAll(['game_id'=>$id]);
        }
        $this->addError('status',$result['message']);
        return  false;
    
    }
    
    
    //加入黑名单
    public function black($id,$status){
        /*$model = self::findOne(['id'=>$id]);
        if(!$model){
            return ['code'=>0,'message'=>'账号不存在!'];
        }*/
        if ($status==2){
            $url = \Yii::$app->params['Api'].'/gameserver/control/removeblack';
        }else{
            $url = \Yii::$app->params['Api'].'/gameserver/control/addblack';
        }
        $datas['playerId']=$id;
        $result = Request::request_post($url,$datas);
        if($result['code'] == 1){
            return true;
           // $model->status=$status;
           // return $model->save(false);  //更新数据变成添加有可能是对象不是之前的
        }
        $this->addError('status',$result['message']);
        return  false;
    }
    
    /**
     * 查询用户超过解封时间的
     */
    public static function automatic(){
        UsersTime::deleteAll(['<','time',time()]);
    }
    
    
    /**
     *    更新用户数据
     */
    public static function UpdateUsers(){
       // $url = \Yii::$app->params['Api'].'/gameserver/control/getAllPlayers';
        //$data = \common\services\Request::request_post($url,['time'=>time()]);
        $re = \common\models\Test::find()->asArray()->all();
        //var_dump($re);EXIT;
        //请求到数据,循环更新数据
        foreach($re as $v)
        {
            //var_dump($v['name']);EXIT;
            if ($model = Users::findOne(['game_id'=>$v['id']])){
                $model->nickname=$v['name'];
                $model->grade=$v['level'];
                $model->gold=$v['gold'];
                $model->gem=$v['fishGold'];
                $model->jewel=$v['diamond'];
                //$model->phone=$v['phone'];
                $model->vip_grade=$v['viplevel'];
                $model->reg_time=strtotime($v['createdtime']);
               // $model->time_day=$v->onlineTime;
                //$model->time_online=$v->totalOnlineTime;
               // var_dump($model);EXIT;
                //var_dump($model);exit;
               // exit;
                
                $model->update(false);
            }else{
                $user = new Users();
                $user->game_id=$v['id'];
                $user->nickname=$v['name'];
                $user->grade=$v['level'];
                $user->gold=$v['gold'];
                $user->gem=$v['fishGold'];
                $user->jewel=$v['diamond'];
                $user->vip_grade=$v['viplevel'];
                $user->reg_time=strtotime($v['createdtime']);
               return  $user->save(false);
            }
        }
        return 1;
    }
    
    /**
     *  获取今日在线时间
     */
    public static function GetDayTime($id,$day=''){
        if ($day){
            $time = Time::find()->select('sum(num)')->andWhere(['typeid'=>10])->andWhere(['time'=>date('Y-m-d')])->andWhere(['playerid'=>$id])->asArray()->all();
            $times =   ceil($time[0]['sum(num)']/1000/60);
        }else{
            $time = Time::find()->select('sum(num)')->andWhere(['typeid'=>10])->andWhere(['playerid'=>$id])->asArray()->all();
            $times =   ceil($time[0]['sum(num)']/1000/60/24);
        }
        return $times;
        
    }
    
    
    /**
     *  查询黑单用户
     */
    public static function GerBack($id){
       return Blacklist::find()->andWhere(['playerId'=>$id])->exists();
    }
    
    
    /**
     *  查询停封用户
     *
     */
    public static function GetBan($id){
       $data = UsersTime::findOne(['game_id'=>$id]);
       if ($data){
           return date('Y-m-d H:i:s',$data->time);
       }else{
           return '';
       }
    }
    
}