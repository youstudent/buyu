<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%currency_pay}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $give_num
 * @property integer $number
 * @property integer $money
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CurrencyPay extends Object
{
    public static $get_type=[1=>'金币',2=>'钻石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'give_num', 'number', 'money', 'manage_id', 'created_at', 'updated_at'], 'integer'],
            [['number','money'],'required'],
            [['number','money'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['manage_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'give_num' => '赠送数量',
            'number' => '购买数量',
            'money' => '人民币',
            'manage_id' => 'Manage ID',
            'manage_name' => 'Manage Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    
    /**
     * 添加充值货币
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->created_at         = time();
            return $this->save();
        }
    }
    
    //充值货币修改
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);
        }
    }
  
}
