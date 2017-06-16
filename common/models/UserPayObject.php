<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_pay}}".
 *
 * @property integer $id
 * @property string $agency_id
 * @property string $agency_name
 * @property integer $user_id
 * @property integer $game_id
 * @property string $nickname
 * @property string $time
 * @property string $gold
 * @property string $money
 * @property integer $status
 * @property integer $gold_config
 * @property integer $type
 * @property UsersObject $user
 * @property AgencyObject $agency
 */
class UserPayObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id', 'user_id', 'game_id', 'time', 'gold', 'status'], 'integer'],
            [['money'], 'number'],
            [['agency_name', 'nickname'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersObject::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyObject::className(), 'targetAttribute' => ['agency_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agency_id' => 'Agency ID',
            'agency_name' => 'Agency Name',
            'user_id' => 'User ID',
            'game_id' => 'Game ID',
            'nickname' => 'Nickname',
            'time' => 'Time',
            'gold' => 'Gold',
            'money' => 'Money',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UsersObject::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(AgencyObject::className(), ['id' => 'agency_id']);
    }
}
