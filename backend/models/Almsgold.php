<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%almsgold}}".
 *
 * @property integer $id
 * @property string $gold
 * @property string $mingold
 * @property integer $receivednum
 * @property integer $type
 */
class Almsgold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%almsgold}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gold', 'mingold', 'receivednum'], 'required'],
            [['gold','mingold','receivednum'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['gold', 'mingold', 'receivednum', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gold' => '数量',
            'mingold' => '最低固值',
            'receivednum' => '次数',
            'type' => 'Type',
        ];
    }
}
