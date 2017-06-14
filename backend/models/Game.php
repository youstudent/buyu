<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "g_game".
 *
 * @property string $id
 * @property integer $num
 * @property string $type
 */
class Game extends \yii\db\ActiveRecord
{
    public static $type=['1'=>'普通模式','2'=>'房卡模式','3'=>'人机胜率','4'=>'分享金币'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_game';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num'], 'integer'],
            [['type'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'num' => '数量/胜率',
            'type' => '类型',
        ];
    }
}
