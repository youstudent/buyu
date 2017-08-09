<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%add_family}}".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $agency_id
 * @property integer $created_at
 * @property integer $status
 */
class AddFamily extends \yii\db\ActiveRecord
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
        return '{{%add_family}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id'], 'required'],
            [['id', 'game_id', 'agency_id', 'created_at', 'status'], 'integer'],
            [['endtime','starttime','select','keyword'],'safe']
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
            'agency_id' => 'Agency ID',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }
    
    
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['>=','created_at',strtotime($this->starttime)])->andWhere(['<=','created_at',strtotime($this->endtime)])->andWhere(['agency_id'=>\Yii::$app->session->get('agencyId')]);
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
     *  和用户建立一对一的关系
     */
    public function getUsers(){
        
        return $this->hasOne(Test::className(),['id'=>'game_id']);
        
    }
    
    
    /**
     * 玩家申请加入家族
     */
    public function add($data =[]){
        if($this->load($data,'') && $this->validate()){
           $this->status=0;
           $this->created_at=time();
           return $this->save();
        }
        
    }
}
