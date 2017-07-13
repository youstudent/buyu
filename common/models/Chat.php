<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property string $id
 * @property string $content
 * @property integer $status
 * @property integer $reg_time
 * @property integer $updated_time
 */
class Chat extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'],'required'],
            [['content','manage_name'], 'string'],
            [['status', 'reg_time','manage_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'status' => '状态',
            'reg_time' => '添加时间',
            'manage_name' => '添加人',
            'manage_id' => '添加人ID',
        ];
    }
    
    /**
     * 添加一个通知
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->reg_time         = time();
            return $this->save();
        }
    }
}
