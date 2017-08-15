<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "batteryrate".
 *
 * @property integer $id
 * @property integer $batteryid
 * @property string $batteryname
 * @property integer $rate
 * @property string $updatetime
 */
class Batteryrate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batteryrate';
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
            [['rate'], 'required'],
            [['batteryid', 'rate'], 'number'],
           // [['rate'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['updatetime'], 'safe'],
            [['batteryname'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batteryid' => 'Batteryid',
            'batteryname' => '炮名字',
            'rate' => '命中率百分之',
            'updatetime' => 'Updatetime',
        ];
    }
}
