<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%users_time}}".
 *
 * @property string $id
 * @property integer $game_id
 * @property string $time
 */
class UsersTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id'], 'integer'],
            [['time'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => '玩家ID',
            'time' => '解封时间',
        ];
    }
}
