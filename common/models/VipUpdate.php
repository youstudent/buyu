<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_update}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $number
 * @property integer $give_gold_num
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $updated_at
 * @property string $grade
 */
class VipUpdate extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_update}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','give_gold_num'],'required'],
            [['number','give_gold_num'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['type', 'number', 'give_gold_num', 'manage_id', 'updated_at'], 'integer'],
            [['manage_name', 'grade'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '等级类型',
            'number' => '购买钻石数量',
            'give_gold_num' => '赠送金币数量',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
            'grade' => '等级范围',
        ];
    }
}
