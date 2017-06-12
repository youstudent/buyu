<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%users_gold}}".
 *
 * @property integer $id
 * @property integer $users_id
 * @property string $gold_config
 * @property string $value
 *
 * @property UsersObject $users
 * @property GoldConfigObject $goldConfig
 */
class UsersGoldObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_gold}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users_id'], 'integer'],
            [['gold'], 'number'],
            [['gold_config'], 'string', 'max' => 32],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersObject::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['gold_config'], 'exist', 'skipOnError' => true, 'targetClass' => GoldConfigObject::className(), 'targetAttribute' => ['gold_config' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Users ID',
            'gold_config' => 'Gold Config',
            'gold' => 'gold',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(UsersObject::className(), ['id' => 'users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoldConfig()
    {
        return $this->hasOne(GoldConfigObject::className(), ['name' => 'gold_config']);
    }
}
