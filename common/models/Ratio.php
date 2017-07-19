<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ratio}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $pay_out
 * @property integer $money
 * @property integer $number
 * @property integer $updated
 * @property integer $manage_id
 * @property string $manage_name
 * @property string $description
 */
class Ratio extends \yii\db\ActiveRecord
{
    public static $ratio=['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ratio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'money', 'number', 'updated_at', 'manage_id'], 'integer'],
            [['pay_out', 'description'], 'string', 'max' => 255],
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
            'type' => '货币类型',
            'pay_out' => '类型',
            'money' => '人民币',
            'number' => '货币数量',
            'updated_at' => '修改时间',
            'manage_id' => 'Manage ID',
            'manage_name' => '操作人',
            'description' => '描述',
        ];
    }
}
