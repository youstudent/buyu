<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rebate}}".
 *
 * @property integer $id
 * @property string $agency_pay_id
 * @property string $agency_pay_name
 * @property string $agency_pay_user_id
 * @property string $agency_id
 * @property string $agency_name
 * @property integer $gold_num
 * @property string $notes
 * @property string $time
 * @property string $rebate_conf
 * @property string $proportion
 *
 * @property AgencyObject $agency
 */
class RebateObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rebate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id', 'time','agency_pay_id','agency_pay_user_id'], 'integer'],
            ['gold_num','number'],
            [['notes'], 'string', 'max' => 255],
            [['rebate_conf'], 'string', 'max' => 11],
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
            'gold_num' => 'Gold Num',
            'notes' => 'Notes',
            'time' => 'Time',
            'rebate_conf' => 'Rebate Conf',
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
