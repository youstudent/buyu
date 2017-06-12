<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%manage}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $phone
 */
class ManageObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '用户名',
            'password' => '用户密码',
        ];
    }
}
