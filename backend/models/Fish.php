<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%fish}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cost
 * @property integer $rate
 * @property integer $groupnum
 * @property integer $maxroute
 * @property integer $aliveTime
 * @property integer $ex
 * @property double $ariserate
 * @property integer $fishtype
 * @property integer $enable
 */
class Fish extends \yii\db\ActiveRecord
{
    public static $give_type = [1=>'小鱼',2=>'中鱼',3=>'大鱼',4=>'金鱼',5=>'BOSS'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fish}}';
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
            [['name', 'cost', 'rate', 'groupnum', 'maxroute', 'aliveTime', 'ex', 'fishtype'], 'required'],
            [['cost','groupnum', 'maxroute', 'aliveTime', 'ex', 'fishtype', 'enable'], 'integer'],
            [['ariserate','rate'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['rate','ariserate'],'vanmuber']
        ];
    }
    /**
     *  验证 小数点
     */
    public function vanmuber(){
        if ($this->rate<0.01 || $this->_getFloatLength($this->rate)>2 || $this->rate>100 || $this->ariserate<0.01 || $this->_getFloatLength($this->ariserate)>2 || $this->ariserate>100 ){
            return  $this->addError('rate','范围0.01-100小数点后两位');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '鱼名',
            'cost' => '价值',
            'rate' => '击杀概率',
            'groupnum' => '鱼群数量',
            'maxroute' => 'Maxroute',
            'aliveTime' => '存活时间',
            'ex' => '经验',
            'ariserate' => '出现概率',
            'fishtype' => '类型',
            'enable' => 'Enable',
        ];
    }
    
    /**
     *  鱼群的修改
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = [])
    {
        if ($this->load($data) && $this->validate()) {
            if ($this->rate > 100 || $this->rate < 0.01) {
                return $this->addError('rate', '击杀概率0.01-100之间');
            }
            if ($this->ariserate > 100 || $this->ariserate < 0.01) {
                return $this->addError('ariserate', '出现概率0.01-100之间');
            }
            if ($this->rate > 10000 || $this->rate < 0.01) {
                return $this->addError('rate', '价值0.01-10000之间');
            }
            
            $this->rate = $this->rate * 100;
            $this->ariserate = $this->ariserate * 100;
            return  $this->save(false);
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
