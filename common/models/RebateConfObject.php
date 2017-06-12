<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rebate_conf}}".
 *
 * @property string $id
 * @property string $one
 * @property integer $two
 * @property integer $sum
 * @property integer $three
 */
class RebateConfObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rebate_conf}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['one', 'two', 'three','sum'], 'required'],
            [['one', 'two', 'three','sum'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sum'=>'返佣比例',
            'one' => '一级代理',
            'two' => '二级代理',
            'three' => '三级代理',
        ];
    }
}
