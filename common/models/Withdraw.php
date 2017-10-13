<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%withdraw}}".
 *
 * @property string $id
 * @property integer $game_id
 * @property integer $phone
 * @property string $nickname
 * @property integer $gold
 * @property integer $status
 * @property integer $bank_card
 * @property string $bank_name
 * @property string $bank_opening
 * @property integer $created_at
 */
class Withdraw extends Object
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
        return '{{%withdraw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'phone', 'gold', 'status', 'bank_card', 'reg_time'], 'integer'],
            [['gold'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['nickname', 'bank_name', 'bank_opening'], 'string', 'max' => 20],
            [['select','keyword','pay_gold_num','pay_gold_config'],'safe'],
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
            'game_id' => '玩家ID',
            'phone' => '手机号码',
            'nickname' => '昵称',
            'gold' => '金额',
            'status' => '状态',
            'bank_card' => '卡号',
            'bank_name' => '卡名',
            'bank_opening' => '卡户行',
            'created_at' => '申请时间',
        ];
    }
    
    
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','reg_time',strtotime($this->starttime)])
            ->andWhere(['<=','reg_time',strtotime($this->endtime)]);
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
     * 搜索处理数据函数
     * @return array
     */
    private function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            
            if ($this->select == 'nickname')
                return ['like','nickname'=>$this->keyword];
            elseif ($this->select == 'phone')
                return ['like','phone',$this->keyword];
            else
                return ['or',['nickname'=>$this->keyword],['like','phone',$this->keyword]];
        }
        return [];
    }
    
    
    /**
     * 处理账号通过或拒绝
     */
    public function pass($id,$status){
        $data = self::findOne(['id'=>$id]);
        if (!$data){
            return ['code'=>0,'message'=>'账号不存在!'];
        }
        if ($status == 2){
            $family = Family::findOne(['id'=>$data->game_id]);
            $pa = Player::findOne(['id'=>$family->owenerid]);
            if ($pa ==false || $pa ==null){
               return ['code'=>0,'message'=>'玩家未找到!'];
            }
            if ( $data->type ==1){
                $pa->gold = $pa->gold+$data->gold;
            }else{
                $pa->fishGold = $pa->fishGold+$data->gold;
            }
            $pa->save();
        }
        $data->status=$status;
        if ($data->save()){
            return ['code'=>1,'message'=>'账号操作成功!'];
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
    }
}
