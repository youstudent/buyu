<?php

namespace common\models;

use api\models\Users;
use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%redeem_record}}".
 *
 * @property string $id
 * @property integer $uid
 * @property string $game_id
 * @property string $nickname
 * @property string $redeem_code
 * @property integer $status
 * @property integer $created_at
 */
class RedeemRecord extends \yii\db\ActiveRecord
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
        return '{{%redeem_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'created_at'], 'integer'],
            [['game_id'], 'string', 'max' => 14],
            [['nickname'], 'string', 'max' => 20],
            [['redeem_code'],'string', 'max' => 255],
            [['select','keyword'],'safe'],
            [['starttime','endtime'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户变自增ID',
            'game_id' => '玩家ID',
            'nickname' => '昵称',
            'redeem_code'=>'兑换码',
            'status'=>'状态',
            'created_at'=>'兑换时间',
        ];
    }
    
    
    /**
     * 兑换列表
     */
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','created_at',strtotime($this->starttime)])
            ->andWhere(['<=','created_at',strtotime($this->endtime)]);
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
            if ($this->select == 'redeem_code'){
                return ['redeem_code'=>$this->keyword];
            }elseif($this->select == 'game_id'){
                return ['game_id'=>$this->keyword];
            }elseif($this->select=='nickname'){
                return ['like','nickname'=>$this->nickname];
            }else{
                return ['or',['game_id'=>$this->keyword],['like','nickname',$this->keyword],['redeem_code'=>$this->keyword]];
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
     * 兑换记录的添加
     */
    public function add($data){
        if ($this->load($data, '') && $this->validate()) {
            $re = RedeemCode::findOne(['redeem_code'=>$this->redeem_code]);
            if ($re->status==0){
                $re->status=1;
                $re->save(false);
            }
            $result= Users::findOne(['game_id'=>$this->game_id]);
            $this->uid=$result->id;//用户表的自增ID
            $this->nickname=$result->nickname;
            $this->created_at=time();
            $this->status=1;
            return $this->save();
        }
    }
    
}

