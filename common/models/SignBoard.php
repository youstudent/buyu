<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sign_board}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $number
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $updated_at
 * @property string $dateil
 */
class SignBoard extends Object
{
    //定义 赠送类型
    public static $get_type=[1=>'金币',2=>'钻石',3=>'宝石',4=>'其他类型'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sign_board}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'number', 'manage_id', 'updated_at'], 'integer'],
            [['number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['detail'], 'string'],
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
            'manage_id' => '修改人ID',
            'manage_name' => '修改人',
            'updated_at' => '更新时间',
            'detail' => '说明',
        ];
    }
}
