<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%toolinfo}}".
 *
 * @property integer $id
 * @property integer $toolid
 * @property string $tooldescript
 * @property integer $unitprice
 * @property integer $buyNum
 * @property integer $minlevel
 * @property integer $minvip
 * @property integer $costtype
 * @property string $toolname
 * @property string $lastTime
 * @property string $cooldown
 * @property string $fromfish
 */
class Toolinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%toolinfo}}';
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
            [['toolid', 'tooldescript', 'unitprice', 'buyNum', 'toolname', 'lastTime', 'cooldown', 'fromfish'], 'required'],
            [['toolid', 'unitprice', 'buyNum', 'minlevel', 'minvip', 'costtype', 'lastTime', 'cooldown'], 'integer'],
            [['minlevel','lastTime','cooldown','costtype','unitprice','minvip'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['tooldescript', 'toolname'], 'string', 'max' => 255],
            [['fromfish'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toolid' => 'Toolid',
            'tooldescript' => '描述',
            'unitprice' => '所需钻石',
            'buyNum' => 'Buy Num',
            'minlevel' => '购买等级',
            'minvip' => '购买等级',
            'costtype' => '所需钻石',
            'toolname' => '道具名',
            'lastTime' => '持续时间[秒]',
            'cooldown' => '冷却时间[秒]',
            'fromfish' => 'Fromfish',
        ];
    }
    
    //修改道具
    public function edit($data=[]){
        if($this->load($data) && $this->validate()){
           return  $this->save();
        }
    }
    
}
