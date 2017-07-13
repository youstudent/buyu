<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%money}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $number
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $updated_at
 */
class Money extends Object
{
    public static $get_type =['1'=>'金币','2'=>'钻石',3=>'发布喇叭所需钻石',4=>'发布留言所需要的钻石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%money}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','detail'], 'required'],
            [['number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['id', 'type', 'number', 'manage_id', 'updated_at'], 'integer'],
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
            'number' => '数量',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
            'detail' => '说明',
        ];
    }
}
