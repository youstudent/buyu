<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%robot}}".
 *
 * @property string $robotid
 * @property integer $level
 * @property string $goldmin
 * @property string $goldmax
 * @property string $diamondmin
 * @property string $diamondmax
 */
class Robot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%robot}}';
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
            [['levelmin', 'goldmin', 'goldmax', 'diamondmin', 'diamondmax','levelmax','staytime','fishgoldmin','fishgoldmax'], 'integer'],
            [['levelmin', 'goldmin', 'goldmax', 'diamondmin', 'diamondmax','levelmax','fishgoldmin','fishgoldmax','powermin','powermax'], 'required'],
            [['goldmin', 'goldmax', 'diamondmin', 'diamondmax','levelmin','levelmax','fishgoldmin','fishgoldmax','powermin','powermax','staytime'], 'validateNum'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'levelmin' => 'vip最小',
            'levelmax' => 'vip最大',
            'goldmin' => '金币最小',
            'goldmax' => '金币最大',
            'diamondmin' => '钻石最小',
            'diamondmax' => '钻石最大',
            'fishgoldmin' => '宝石最小',
            'fishgoldmax' => '宝石最大',
            'powermin'    => '炮倍最小',
            'powermax'    => '炮倍最大',
            'staytime' => '停留时间',
        ];
    }
    
    /**
     *  限制参数
     */
    public function validateNum(){
        if ($this->levelmin<0 || $this->levelmax<0 || $this->goldmin<0 || $this->goldmax<0 ){
            $this->addError('goldmin','数量不能小于0');
        }
        if ($this->diamondmin<0 || $this->diamondmax<0 || $this->fishgoldmin<0 || $this->fishgoldmax<0 ){
            $this->addError('goldmin','数量不能小于0');
        }
        if ($this->powermin<0 || $this->powermax<0 || $this->staytime<0){
            $this->addError('goldmin','数量不能小于0');
        }
        
    }
    
    
    /**
     * 修改 机器人参数配置
     * @param $data
     * @return bool
     */
    public function edit($data){
       if ($this->load($data) && $this->validate()){
          return  $this->save();
       }
       
    }
}
