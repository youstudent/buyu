<?php

namespace common\models;

use backend\models\PrewarningValue;
use backend\models\Users;
use common\helps\players;
use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%on_line}}".
 *
 * @property string $id
 * @property integer $game_id
 * @property integer $room_id
 * @property integer $number_num
 */
class OnLine extends \yii\db\ActiveRecord
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
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%on_line}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'room_id', 'number_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'room_id' => 'Room ID',
            'number_num' => 'Number Num',
        ];
    }
    
    /**
     *   在线用户和用户建立一对一的关系
     */
    public function getUsers(){
    
        return $this->hasOne(Users::className(),['game_id'=>'game_id']);
    
    }
    
    
    /**
     * @param $id
     * @return mixed
     * 获取玩家房间号
     */
    public static function getRoom($id){
        $redis = self::getReids();
        $data =$redis->HGETALL('playerRoom')[$id];
        return $data;
    }
    
    
    /**
     * @param $room
     * @return mixed
     * 获取玩家房间人数
     */
    public static function getRoomNmu($room){
        $redis = self::getReids();
        $data =$redis->HGETALL('roomPlayerNum')[$room];
        return $data;
    }
    
    
    /**
     * @return \Redis
     *  连接redis
     */
    public static function getReids(){
        $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        return $redis;
    }
    
    
    /**
     * 搜索并分页显示用户的数据.报警用户
     * @return array
     */
    public function getList($data = [])
    {
        
        $this->load($data);
        $this->initTime();
        $redis = players::getReids();
        $Player = $redis->SMEMBERS('onlinePlayer');//获取在线的人数
        //$datas=[2,60,254,26,27,28];
        $new_data=[];
        foreach ($Player as $va){
            $re =  PrewarningValue::findOne(['game_id'=>$va]);
            $row = Player::findOne(['id'=>$va]);
            if (!$re){
                $PrewarningValue = new PrewarningValue();
                $PrewarningValue->game_id=$va;
                $PrewarningValue->fishgold=10000;
                $PrewarningValue->gold=100000;
                $PrewarningValue->save(false);
            }
            if ($re->game_id == $row->id){
                if ($row->gold > $re->gold || $row->fishGold > $re->fishgold){
                    $new_data[]=$row->id;
                }
            }
        }
        $filed = 'GREATEST(`gold`,`diamond`,`fishGold`) as t,id,name,gold,diamond,fishGold';
        $model = Player::find()->select($filed)->orderBy(['t'=> SORT_DESC]);
        $model->andWhere(['id' => $new_data])->andWhere($this->searchWhere())->andWhere(['>=','createdtime',$this->starttime])->andWhere(['<=','createdtime',$this->endtime]);
        $data  = $model->all();
        return ['data'=>$data,'model'=>$this];
    }
    
    
    /**
     * 搜索并分页显示用户的数据, 在线用户
     * @return array
     */
    public function getLists($data = [])
    {
        
        $this->load($data);
        $this->initTime();
        $redis = players::getReids();
        $Player = $redis->SMEMBERS('onlinePlayer');//获取在线的人数
        //var_dump($Player);exit;
       // var_dump($Player);EXIT;
       // $datas=[2,60,254,26,27,28];
      //  var_dump($Player);exit;
        $new_data=[];
        foreach ($Player as $va){
           $re =  PrewarningValue::findOne(['game_id'=>$va]);
           if (!$re){
               $PrewarningValue = new PrewarningValue();
               $PrewarningValue->game_id=$va;
               $PrewarningValue->fishgold=10000;
               $PrewarningValue->gold=100000;
               $PrewarningValue->save(false);
           }
           $row = Player::findOne(['id'=>$va]);
           if ($re->game_id == $row->id){
               if ($row->gold<=$re->gold && $row->fishGold<=$re->fishgold){
                       $new_data[]=$row->id;
               }
               
           }
        }
        //var_dump($new_data);exit;
        $filed = 'GREATEST(`gold`,`diamond`,`fishGold`) as t,id,name,gold,diamond,fishGold';
        $model = Player::find()->select($filed)->orderBy(['t'=> SORT_DESC]);
        $model->andWhere(['id' => $new_data])->andWhere($this->searchWhere())->andWhere(['>=','createdtime',$this->starttime])->andWhere(['<=','createdtime',$this->endtime]);
        $data  = $model->all();
        return ['data'=>$data,'model'=>$this];
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
                return ['like','uid',$this->keyword];
            else
                return ['or',['id'=>$this->keyword],['like','uid',$this->keyword],['like','name',$this->keyword]];
        }
        return [];
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
}
