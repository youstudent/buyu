<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%touch}}".
 *
 * @property string $id
 * @property integer $phone
 * @property integer $qq_number
 * @property string $manage_name
 * @property integer $manage_id
 * @property string $hkmovie
 * @property integer $status
 * @property integer $updated_at
 */
class Touch extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%touch}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone','qq_number','hkmovie'],'required'],
            [['qq_number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'QQ号码不能是负数'],
            [['qq_number', 'manage_id', 'status', 'updated_at'], 'integer'],
            [['manage_name', 'hkmovie','phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => '联系电话',
            'qq_number' => 'QQ号码',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'hkmovie' => '公众号',
            'status' => '状态',
            'updated_at' => '修改时间',
        ];
    }
}
