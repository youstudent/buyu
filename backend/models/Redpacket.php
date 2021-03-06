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
    public $types;
    public static $option=[1=>'小鱼',2=>'中鱼',3=>'大鱼',4=>'金鱼',5=>'BOSS'];
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
            [['fishid', 'rate', 'minnum', 'maxnum','dropmin','dropmax'], 'required'],
            [['fishid', 'rate', 'minnum', 'maxnum','type','dropmin','dropmax'], 'number'],
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
            'minnum' => '掉落最小范围',
            'maxnum' => '掉落最大范围',
            'type' => '鱼类型',
            'dropmin' => '掉落个数最小范围',
            'dropmax' => '掉落个数最大范围',
        ];
    }
    
    /**
     * 添加红包
     * @param array $data
     */
    public function add($data=[]){
        if ($this->load($data) &&$this->validate()){
           
            if ($this->_getFloatLength($this->rate)>2){
                return $this->addError('rate','出现概率小数点后两位');
            }
            if ($this->rate<0.01 || $this->rate>100){
                return $this->addError('rate','出现概率在0.01-100之间');
            }
            if ($this->minnum<0.01 || $this->minnum>100 || $this->maxnum<0.01 || $this->maxnum>100 ){
                return $this->addError('minnum','掉落区间在0.01-100之间');
            }
            if ($this->dropmin<0.001 || $this->dropmax>100 || $this->dropmin>100 || $this->dropmax<0.001){
                return $this->addError('minnum','个数区间在0.001-100之间');
            }
            if ($this->minnum>$this->maxnum){
                return $this->addError('minnum','掉落最小区间小于大区间');
            }
            if ($this->dropmin>$this->dropmax){
                return $this->addError('minnum','个数最小区间小于大区间');
            }
            $this->rate=$this->rate*100; //出现概率
            $this->minnum=$this->minnum*100;
            $this->maxnum=$this->maxnum*100;
            $this->dropmin=$this->dropmin*100;
            $this->dropmax=$this->dropmax*100;
            return $this->save();
        }
    }
    
    private function _getFloatLength($num) {
        $count = 0;
        
        $temp = explode ( '.', $num );
        
        if (sizeof ( $temp ) > 1) {
            $decimal = end ( $temp );
            $count = strlen ( $decimal );
        }
        
        return $count;
    }
    
    
    
}
