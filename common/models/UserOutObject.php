<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_out}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $game_id
 * @property string $nickname
 * @property string $time
 * @property string $gold
 * @property string $game_class
 * @property string $gold_config
 *
 * @property UsersObject $user
 * @property GameClassObject $gameClass
 */
class UserOutObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_out}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'game_id', 'time', 'gold'], 'integer'],
            [['nickname', 'game_class'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersObject::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['game_class'], 'exist', 'skipOnError' => true, 'targetClass' => GameClassObject::className(), 'targetAttribute' => ['game_class' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '玩家ID',
            'game_id' => '玩家ID',
            'nickname' => '玩家昵称',
            'time' => '注册时间',
            'gold' => '剩余金币',
            'game_class' => '总计金币',
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
    public function getGameClass()
    {
        return $this->hasOne(GameClassObject::className(), ['name' => 'game_class']);
    }
}
