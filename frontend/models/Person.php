<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%person}}".
 *
 * @property string $id
 * @property integer $game_id
 * @property string $phone
 * @property integer $bank_card
 * @property string $bank_name
 * @property string $bank_opening
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%person}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'bank_card'], 'integer'],
            [['phone', 'bank_name'], 'string', 'max' => 20],
            [['bank_opening'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'phone' => '手机号码',
            'bank_card' => '银行卡',
            'bank_name' => '银行卡用户名',
            'bank_opening' => '开户行',
        ];
    }
}
