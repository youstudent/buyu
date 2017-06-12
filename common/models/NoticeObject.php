<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $manage_id
 * @property string $manage_name
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property string $time
 * @property string $notes
 * @property string $location
 */
class NoticeObject extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
//        required
        return [
            [['manage_id', 'status', 'time'], 'integer'],
            [['content'], 'string'],
            [['manage_name'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['notes', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manage_id' => '管理员ID',
            'manage_name' => '管理员名称',
            'title' => '标题',
            'content' => '内容',
            'status' => '状态',
            'time' => '添加时间',
            'notes' => '备注',
            'location' => '位置',
        ];
    }
}
