<?php

namespace common\models;

use Yii;
use yii\data\Pagination;
use yii\db\Exception;

/**
 * This is the model class for table "familyplayer".
 *
 * @property integer $id
 * @property integer $familyid
 * @property integer $playerid
 * @property integer $status
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $applytime
 * @property string $agreetime
 */
class Familyplayer extends \yii\db\ActiveRecord
{
    
    
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
        return 'familyplayer';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        //return Yii::$app->get('commondb');
        return Yii::$app->commondb;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['familyid', 'playerid'], 'required'],
            [['familyid', 'playerid', 'status', 'gold', 'diamond', 'fishgold','operattime','id','position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'familyid' => '家族ID',
            'playerid' => '玩家ID',
            'status' => '状态',
            'gold' => '玩家保险箱金币',
            'diamond' => '钻石',
            'fishgold' => '宝石',
            'operattime' => '修改时间',
            'position' => '是否族长',
        ];
    }
    
    /**
     *  获取家族成员
     * @param array $data
     * @return array
     */
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['>=','operattime',$this->starttime])->andWhere(['<=','operattime',$this->endtime])->andWhere(['familyid'=>\Yii::$app->session->get('familyId'),'status'=>1])->orderBy('position DESC');
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
     *  家族申请
     * @param array $data
     * @return array
     */
    public function getApply($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['familyid'=>\Yii::$app->session->get('familyId'),'status'=>$this->status]);
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
     * @param array $data
     * @return array
     */
    public function son($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['>=','operattime',$this->starttime])->andWhere(['<=','operattime',$this->endtime])->andWhere(['familyid'=>$this->id,'status'=>1]);
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
            $this->starttime = \Yii::$app->params['startTime'];
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    
    /**
     *   族长玩家关系表, 建立玩家关系表
     */
    public function getSon(){
        return $this->hasOne(Player::className(),['id'=>'playerid']);
    }
    
    
    /**
     *  处理玩家通过还是拒绝
     */
    public function pass($id,$status){
        $data = self::findOne(['id'=>$id]);
        if (!$data){
            return ['code'=>0,'message'=>'账号不存在!'];
        }
        /**
         *  添加通过或者拒绝操作
         */
        if ($re =  self::FamilyRecord($data,$status,0,0,0)){
             $data->status=$status;
            if ($data->save(false)){
                return ['code'=>1,'message'=>'账号操作成功!'];
            }
        }
            return ['code'=>0,'message'=>'账号操作失败!'];
    }
    
    
    /**
     *  踢出玩家 并退还玩家 存入保险箱的钱
     */
    public function kickOut($id,$stats){
        $transaction  = \Yii::$app->db->beginTransaction();
        try{
            $data = self::findOne(['id'=>$id]);
            $Player= Player::findOne(['id'=>$data->playerid]);
            if ($Player ==false || $Player==null){
                throw  new \Exception("玩家不存在");
            }
            $Player->gold=($Player->gold+$data->gold);
            $Player->diamond=($Player->diamond+$data->diamond);
            $Player->fishGold=($Player->fishGold+$data->fishgold);
            if (!$Player->save()){
                throw new Exception('退换保险箱货币失败');
            }
            //踢出玩家记录
            $FamilyRecord= new Familyrecord();
            $FamilyRecord->playerid=$data->playerid;
            $FamilyRecord->familyid=Yii::$app->session->get('familyId');
            $FamilyRecord->type=4;
            $FamilyRecord->gold=$data->gold;
            $FamilyRecord->diamond=$data->diamond;
            $FamilyRecord->fishgold=$data->fishgold;
            if (!$FamilyRecord->save()){
                throw new Exception('踢出记录失败');
            }
            $data->status=$stats;
            $data->gold=0;
            $data->diamond=0;
            $data->fishgold=0;
            if (!$data->save(false)){
                throw new Exception('状态改变失败');
            }
            $transaction->commit();
            return true;
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
       
    }
    
    
    /**
     *  执行记录操作,记录家族动态
     */
    public static function FamilyRecord($data,$type,$gold,$diamond,$fishgold){
        $FamilyRecord = new Familyrecord();
        $FamilyRecord->playerid=$data->playerid;
        $FamilyRecord->familyid=$data->familyid;
        $FamilyRecord->type=$type;
        $FamilyRecord->gold=$gold;
        $FamilyRecord->diamond=$diamond;
        $FamilyRecord->fishgold=$fishgold;
       return $FamilyRecord->save(false);
    }
    
}
