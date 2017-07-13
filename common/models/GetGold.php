<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%get_gold}}".
 *
 * @property string $id
 * @property integer $lowest
 * @property integer $type
 * @property integer $number
 * @property integer $updated_at
 */
class GetGold extends  Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%get_gold}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','lowest'],'required'],
            [['number','lowest'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['lowest', 'type', 'number', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lowest' => '最低谷值',
            'type' => '类型',
            'number' => '领取数量',
            'updated_at' => '更新时间',
        ];
    }
}
