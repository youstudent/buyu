<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%day}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $give_type
 * @property string $day
 * @property integer $gold_num
 * @property integer $jewel_num
 * @property integer $salvo_num
 * @property integer $updated_at
 * @property string $manage_name
 * @property integer $manage_id
 */
class Day extends Object
{
    public static $get_type=[1=>'一次性使用',2=>'固定使用奖励数值'];
    public static $get_give_type=[1=>'金币',2=>'钻石',3=>'礼炮'];
    public static $get_gives_type=[1=>'金币',2=>'钻石',3=>'礼炮',12=>'金币,钻石',13=>'金币,礼炮',123=>'金币,钻石,礼炮',23=>'钻石,礼炮',''=>''];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%day}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','gold_num', 'jewel_num', 'salvo_num', 'updated_at', 'manage_id'], 'integer'],
            [['day', 'manage_name'], 'string', 'max' => 20],
            [['gold_num','jewel_num','salvo_num'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['give_type'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '领取类型',
            'give_type' => '赠送类型',
            'day' => '签到天数',
            'gold_num' => '金币数量',
            'jewel_num' => '钻石数量',
            'salvo_num' => '礼炮数量',
            'updated_at' => '修改时间',
            'manage_name' => '修改人',
            'manage_id' => '修改ID',
        ];
    }
}
