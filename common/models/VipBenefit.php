<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_benefit}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $number
 * @property string $grade
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $updated_at
 */
class VipBenefit extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_benefit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'number', 'manage_id', 'updated_at'], 'integer'],
            [['number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['number'], 'required'],
            [['grade', 'manage_name'], 'string', 'max' => 20],
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
            'number' => '数量',
            'grade' => 'vip等级',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
        ];
    }
}
