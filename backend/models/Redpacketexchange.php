<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%redpacketexchange}}".
 *
 * @property integer $id
 * @property integer $redpacketnum
 * @property string $prize
 */
class Redpacketexchange extends \yii\db\ActiveRecord
{
    public static $option = [1=>'兑换金币',2=>'兑换钻石',3=>'兑换宝石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%redpacketexchange}}';
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
            //[['redpacketnum'],'unique'],
            [['redpacketnum','num','type'], 'required'],
            [['redpacketnum'], 'integer'],
            [['prize'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'redpacketnum' => '红包个数',
            //'prize' => '金币数量',
            'num' => '数量',
            'type'=>'类型'
        ];
    }
    
    /**
     *  添加红包个数
     * @param array
     */
    public function add($data=[]){
        if ($this->load($data) &&$this->validate()){
            if ($this->redpacketnum<=0 || $this->num<=0){
                return $this->addError('num','数量无效');
            }
            //$datas['gold']=$this->prize;
           // $this->prize = json_encode($datas);
            return $this->save();
        }
    }
    
    /**
     * 格式化 金币
     * @param $prize
     * @return mixed
     */
    public static function prize($prize){
        $row = json_decode($prize,true);
        return $row['gold'];
    }
}
