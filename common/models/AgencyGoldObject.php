<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%agency_gold}}".
 *
 * @property integer $id
 * @property integer $agency_id
 * @property string $gold_config
 * @property string $gold
 * @property string $sum_gold
 *
 * @property GoldConfigObject $goldConfig
 * @property AgencyObject $agency
 */
class AgencyGoldObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency_gold}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id'], 'required'],
            [['agency_id'], 'integer'],
            [['gold', 'sum_gold'], 'number'],
            [['gold_config'], 'string', 'max' => 32],
            [['gold_config'], 'exist', 'skipOnError' => true, 'targetClass' => GoldConfigObject::className(), 'targetAttribute' => ['gold_config' => 'name']],
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
            'gold_config' => 'Gold Config',
            'gold' => 'Gold',
            'sum_gold' => 'Sum Gold',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoldConfig()
    {
        return $this->hasOne(GoldConfigObject::className(), ['name' => 'gold_config']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(AgencyObject::className(), ['id' => 'agency_id']);
    }
}
