<?php

namespace backend\models;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;

/**
 * This is the model class for table "{{%redpacket}}".
 *
 * @property integer $id
 * @property integer $fishid
 * @property integer $rate
 * @property integer $minnum
 * @property integer $maxnum
 */
class Redpacket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%redpacket}}';
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
            [['fishid', 'rate', 'minnum', 'maxnum'], 'required'],
            [['fishid', 'rate', 'minnum', 'maxnum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fishid' => '红包鱼',
            'rate' => '出现概率',
            'minnum' => '最小范围',
            'maxnum' => '最大范围',
        ];
    }
    
    /**
     * 添加红包
     * @param array $data
     */
    public function add($data=[]){
        if ($this->load($data) &&$this->validate()){
            if ($this->rate<1 || $this->rate>100){
                return $this->addError('rate','出现概率在1-100之间');
            }
            if ($this->minnum<0.001 || $this->minnum>0.9 || $this->maxnum<0.001 || $this->maxnum>0.9){
                return $this->addError('minnum','区间在0.001-0.9之间');
            }
            $this->rate=$this->rate*100; //出现概率
            $this->minnum=$this->minnum*100;
            $this->maxnum=$this->maxnum*100;
            return $this->save();
        }
    }
    
    
    
}
