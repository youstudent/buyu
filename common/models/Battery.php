<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $multiple
 * @property integer $number
 * @property integer $give_gold_num
 * @property integer $updated_at
 * @property integer $manage_id
 * @property string $manage_name
 */
class Battery extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','give_gold_num'],'required'],
            [['multiple', 'number', 'give_gold_num', 'updated_at', 'manage_id'], 'integer'],
            [['number','give_gold_num','multiple'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['name', 'manage_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名字',
            'multiple' => '炮台倍数',
            'number' => '宝石数量',
            'give_gold_num' => '赠送金币数量',
            'updated_at' => '修改时间',
            'manage_id' => '修改人ID',
            'manage_name' => '修改人',
        ];
    }
}
