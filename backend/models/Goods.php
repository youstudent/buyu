<?php

namespace backend\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "g_goods".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $name
 * @property string $phone
 * @property string $exchange
 * @property integer $status
 * @property integer $created_at
 * @property string $detail
 * @property integer $updated_ta
 */
class Goods extends \yii\db\ActiveRecord
{
    
    public $keyword = '';
    
    public $select  = '';
    
    public $starttime     = '';
    
    public $endtime     = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_goods';
    }
    
    
  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_ta'], 'integer'],
            [['name'], 'required'],
            [['name', 'exchange'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 11],
            [['detail'], 'string', 'max' => 255],
            [['keyword','select','endtime','starttime'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '玩家ID',
            'name' => '昵称',
            'phone' => '电话',
            'exchange' => '兑换奖品',
            'status' => '状态',
            'created_at' => '兑换时间',
            'detail' => '备注',
            'updated_ta' => '处理时间',
        ];
    }
    
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
    
    
    public function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'user_id')
                return ['user_id'=>$this->keyword];
            elseif ($this->select == 'name')
                return ['like','name',$this->keyword];
            else
                return ['or',['user_id'=>$this->keyword],['like','name',$this->keyword]];
        }
        return [];
    }
    
    
}
