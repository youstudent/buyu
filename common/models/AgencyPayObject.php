<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%agency_pay}}".
 *
 * @property integer $id
 * @property string $agency_id
 * @property string $name
 * @property string $time
 * @property string $gold
 * @property string $money
 * @property string $notes
 * @property integer $status
 * @property integer $manage_id
 * @property string $manage_name
 * @property string $gold_config
 *
 * @property AgencyObject $agency
 */
class AgencyPayObject extends Object
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id', 'time', 'gold', 'status'], 'integer'],
            [['money'], 'number'],
            [['notes','gold_config'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyObject::className(), 'targetAttribute' => ['agency_id' => 'id']],
            [['type'],'safe']
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
            'name' => 'Name',
            'time' => 'Time',
            'gold' => 'Gold',
            'money' => 'Money',
            'notes' => 'Notes',
            'status' => 'Status',
            'type' => 'type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(AgencyObject::className(), ['id' => 'agency_id']);
    }
}
