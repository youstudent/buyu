<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $diamond
 * @property string $gold
 * @property string $fishgold
 * @property string $toolid
 * @property string $toolNum
 * @property string $createDate
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email}}';
    }

    
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
            [['title', 'content'], 'required'],
            [['diamond', 'gold', 'fishgold'], 'integer'],
            [['createDate'], 'safe'],
            [['title', 'content', 'toolid', 'toolNum'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'diamond' => 'Diamond',
            'gold' => 'Gold',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'toolNum' => 'Tool Num',
            'createDate' => 'Create Date',
        ];
    }
}
