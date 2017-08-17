<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%prewarning_value}}".
 *
 * @property string $id
 * @property integer $gold
 * @property integer $diamond
 * @property integer $fishgold
 * @property integer $game_id
 */
class PrewarningValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prewarning_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gold','fishgold'],'required'],
            [['fishgold','gold'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['gold', 'diamond', 'fishgold', 'game_id'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gold' => '金币',
            'diamond' => '钻石',
            'fishgold' => '宝石',
            'game_id' => '用户ID',
        ];
    }
    
    
    public function editRate($data){
       if ($this->load($data) && $this->validate()){
           return $this->save();
       }
    
    }
}
