<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "roomrate".
 *
 * @property integer $id
 * @property integer $muti
 * @property integer $minrate
 * @property string $updatetime
 * @property integer $maxrate
 */
class Roomrate extends \yii\db\ActiveRecord
{
    public $number;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roomrate';
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
           // [['muti', 'minrate', 'maxrate'], 'required'],
            [['muti', 'minrate', 'maxrate'], 'number'],
            [['updatetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'muti' => '房间倍数',
            'minrate' => '最小命中率%',
            'updatetime' => 'Updatetime',
            'maxrate' => '最大命中率%',
            'number' => '房间倍数',
        ];
    }
}
