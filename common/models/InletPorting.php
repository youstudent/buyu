<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%inlet_porting}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $status
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $updated_at
 */
class InletPorting extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inlet_porting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'manage_id', 'updated_at','type'], 'integer'],
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
            'name' => '游戏名',
            'status' => '状态',
            'manage_id' => '修改人ID',
            'manage_name' => '修改人',
            'updated_at' => '修改时间',
        ];
    }
}
