<?php

namespace backend\models;

use common\helps\getgift;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;

/**
 * This is the model class for table "{{%exchangegold}}".
 *
 * @property integer $id
 * @property string $needdiamond
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property string $toolnum
 */
class Exchangegold extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchangegold}}';
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
            [['needdiamond'], 'required'],
            [['needdiamond'],'unique'],
            [['needdiamond'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'数量必须大于0'],
            [['type','gift'], 'safe'],
            [['needdiamond', 'gold', 'diamond', 'fishgold'], 'integer'],
            [['toolid', 'toolnum'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'needdiamond' => '金币兑换等级',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'toolnum' => 'Toolnum',
            'gift' => '礼包',
        ];
    }
    
    /**
     * 添加 钻石兑换金币等级
     * @param array $data
     * @return bool
     */
    public function add($data=[]){
        if ($this->load($data) && $this->validate()){
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->toolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->toolnum = $re['toolNum'];
                    }
                    $this->gold=$re['gold'];
                    $this->diamond=$re['diamond'];
                    $this->fishgold=$re['fishgold'];
                }else{
                    return $this->addError('gift',$getGift->message);
                }
            }
            return $this->save();
        }
    }
    
    /**
     * 修改 钻石兑换金币等级
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $this->gold=0;
            $this->diamond=0;
            $this->fishgold=0;
            $this->toolid='';
            $this->toolnum='';
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->toolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->toolnum = $re['toolNum'];
                    }
                    $this->gold=$re['gold'];
                    $this->diamond=$re['diamond'];
                    $this->fishgold=$re['fishgold'];
                }else{
                    return $this->addError('gift',$getGift->message);
                }
            }
            return $this->save();
        }
    }
}
