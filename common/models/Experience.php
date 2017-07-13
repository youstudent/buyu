<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%experience}}".
 *
 * @property string $id
 * @property integer $grade
 * @property integer $type
 * @property integer $number
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class Experience extends  Object
{
    public static $get_type=[1=>'金币',2=>'钻石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%experience}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade', 'type', 'number', 'manage_id', 'created_at', 'updated_at'], 'integer'],
            [['grade','number'],'required'],
            [['grade'],'unique'],
            [['grade','number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['manage_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade' => '经验等级',
            'type' => '类型',
            'number' => '数量',
            'manage_id' => 'Manage ID',
            'manage_name' => 'Manage Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            $this->created_at         = time();
            return $this->save();
        }
    }
    
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
           
            return $this->save();
        }
    }
}
