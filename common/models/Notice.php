<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property string $content
 * @property string $createDate
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property integer $noticetype
 * @property string $toolNum
 * @property integer $enable
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
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
            [['content', 'noticetype'], 'required'],
            [['createDate'], 'safe'],
            [['gold', 'diamond', 'fishgold', 'noticetype', 'enable'], 'integer'],
            [['content', 'toolid', 'toolNum'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'createDate' => 'Create Date',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'noticetype' => 'Noticetype',
            'toolNum' => 'Tool Num',
            'enable' => 'Enable',
        ];
    }
}
