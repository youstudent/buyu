<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%contract}}".
 *
 * @property integer $id
 * @property string $telephone
 * @property string $qq
 * @property string $appcontract
 * @property string $createtime
 * @property integer $status
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contract}}';
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
            [['createtime'], 'safe'],
            [['status'], 'integer'],
            [['telephone', 'qq'], 'string', 'max' => 16],
            [['appcontract'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telephone' => '联系电话',
            'qq' => 'QQ',
            'appcontract' => '微信号',
            'createtime' => 'Createtime',
            'status' => '状态',
        ];
    }
}
