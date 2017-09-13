<?php

namespace backend\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%redpacketrecord}}".
 *
 * @property integer $id
 * @property integer $playerid
 * @property integer $type
 * @property integer $num
 * @property integer $fromplayerid
 * @property integer $toplayerid
 * @property string $time
 */
class Redpacketrecord extends \yii\db\ActiveRecord
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
        return '{{%redpacketrecord}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->commondb;
       // return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playerid', 'type', 'num', 'fromplayerid', 'toplayerid'], 'required'],
            [['playerid', 'type', 'num', 'fromplayerid', 'toplayerid'], 'integer'],
            [['time','endtime','start_time','keyword','select'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playerid' => '赠送人ID',
            'type' => '类型',
            'num' => '数量',
            'fromplayerid' => '赠送人ID',
            'toplayerid' => '被赠送人ID',
            'time' => '时间',
        ];
    }
    
    /**
     * 搜索并分页红包获得的数据
     * @return array
     */
    public function getList($data = [])
    {
        //var_dump($data);
        $this->load($data);
        $this->initTime();
        $model   = Redpacketrecord::find()->andWhere(['type'=>[1,3]])->andWhere($this->searchWhere())
            ->andWhere(['>=','time',$this->starttime])
            ->andWhere(['<=','time',$this->endtime]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('id ASC')->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    /**
     * 搜索并分页红包获得的数据
     * @return array
     */
    public function getLose($data = [])
    {
        //var_dump($data);
        $this->load($data);
        $this->initTime();
        $model   = Redpacketrecord::find()->andWhere(['type'=>[2,4]])->andWhere($this->searchWhere())
            ->andWhere(['>=','time',$this->starttime])
            ->andWhere(['<=','time',$this->endtime]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('id ASC')->all();
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
                return ['like','playerid',$this->keyword];
            else
                return ['or',['like','playerid',$this->keyword]];
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
