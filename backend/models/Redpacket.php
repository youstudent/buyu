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
            [['fishid', 'rate', 'minnum', 'maxnum','floprateminnum','flopratemaxnum'], 'required'],
            [['fishid', 'rate', 'minnum', 'maxnum','type','floprateminnum','flopratemaxnum'], 'number'],
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
            'floprateminnum' => '掉落个数最小范围',
            'flopratemaxnum' => '掉落个数最大范围',
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
            if ($this->minnum<0.001 || $this->minnum>1 || $this->maxnum<0.001 || $this->maxnum>1 || $this->floprateminnum<0.001 || $this->flopratemaxnum>1){
                return $this->addError('minnum','区间在0.001-0.99之间');
            }
            $this->rate=$this->rate*100; //出现概率
            $this->minnum=$this->minnum*100;
            $this->maxnum=$this->maxnum*100;
            $this->floprateminnum=$this->floprateminnum*100;
            $this->flopratemaxnum=$this->flopratemaxnum*100;
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
