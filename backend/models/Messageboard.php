<?php

namespace backend\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%messageboard}}".
 *
 * @property integer $id
 * @property integer $playerid
 * @property string $message
 * @property string $messageDate
 */
class Messageboard extends \yii\db\ActiveRecord
{
    public $keyword;
    public $starttime;
    
    public $endtime;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messageboard}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playerid', 'message'], 'required'],
            [['playerid'], 'integer'],
            [['messageDate','starttime','endtime'], 'safe'],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playerid' => 'Playerid',
            'message' => 'Message',
            'messageDate' => 'Message Date',
        ];
    }
    
    public function getList($data){
        $this->load($data);
        $this->initTime();
        $model   = self::find()
            ->andWhere(['>=','messageDate',$this->starttime])
            ->andWhere(['<=','messageDate',$this->endtime]);
        $pages   = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data    = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
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
}
