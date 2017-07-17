<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property string $toolName
 * @property string $toolDescript
 * @property string $unitPrice
 * @property string $minLevel
 * @property string $minVip
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unitPrice'], 'number'],
            [['toolName', 'toolDescript', 'minLevel', 'minVip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'toolName' => 'Tool Name',
            'toolDescript' => 'Tool Descript',
            'unitPrice' => 'Unit Price',
            'minLevel' => 'Min Level',
            'minVip' => 'Min Vip',
        ];
    }
}
