<?php

namespace api\models;

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
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'updated_at' => '处理时间',
        ];
    }
    
    
    // 兑换的添加
    public function add($data)
    {
        if($this->load($data,'') && $this->validate()) {
           /* $this->name = $data['name'];
            $this->user_id = $data['user_id'];
            $this->phone = $data['phone'];  //电话
            $this->exchange = $data['exchange']; //奖品
            $this->detail = $data['detail'];*/
            $this->created_at = time();
            $this->status = 1;
            if ($this->save()) {
                return true;
            }
        }
            return false;
    }
    
    
}
