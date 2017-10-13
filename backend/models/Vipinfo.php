<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%vipinfo}}".
 *
 * @property integer $id
 * @property integer $viplevel
 * @property integer $vipex
 * @property string $ability
 * @property string $abilitydesc
 * @property integer $killrate
 * @property string $gold
 * @property string $upgold
 * @property string $updiamond
 * @property string $upfishgold
 * @property string $uptoolid
 * @property string $uptoolnum
 * @property string $fishgold
 * @property string $diamond
 * @property string $toolid
 * @property string $toolnum
 * @property integer $almsrate
 * @property integer $almsnum
 */
class Vipinfo extends \yii\db\ActiveRecord
{
    public $type;
    
    public $types;
    
    public $gift;
    
    public $gifts;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vipinfo}}';
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
            [['viplevel', 'vipex', 'ability', 'abilitydesc', 'gold', ], 'required'],
            [['viplevel', 'vipex',  'gold', 'upgold', 'updiamond', 'upfishgold', 'fishgold', 'diamond', 'almsnum'], 'integer'],
            [['ability'], 'string', 'max' => 11],
            [['abilitydesc', 'uptoolid', 'uptoolnum', 'toolid', 'toolnum'], 'string', 'max' => 255],
            [['gift','gifts','type','types','upgold', 'updiamond', 'upfishgold', 'uptoolid', 'uptoolnum', 'fishgold', 'diamond', 'toolid', 'toolnum'],'safe'],
            [['killrate','almsrate','almsnum'],'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'viplevel' => 'vip等级',
            'vipex' => '充值人民币',
            'ability' => 'Ability',
            'abilitydesc' => 'Abilitydesc',
            'killrate' => '暴率',
            'gold' => 'Gold',
            'upgold' => 'Upgold',
            'updiamond' => 'Updiamond',
            'upfishgold' => 'Upfishgold',
            'uptoolid' => 'Uptoolid',
            'uptoolnum' => 'Uptoolnum',
            'fishgold' => 'Fishgold',
            'diamond' => 'Diamond',
            'toolid' => 'Toolid',
            'toolnum' => 'Toolnum',
            'almsrate' => '救济金比例',
            'almsnum' => '救济金次数',
            'gift' => '升级礼包',
            'gifts' => '每日礼包',
        ];
    }
    
    public function edit($data =[]){
        if($this->load($data) && $this->validate())
        {
           
            if ($this->killrate<0 || $this->almsrate>100 || $this->killrate>100 ||$this->almsrate<0){
                return $this->addError('killrate','爆率和救济金领取比例在0-100之间');
            }
            $this->upgold=0;
            $this->updiamond=0;
            $this->upfishgold=0;
            $this->uptoolid='';
            $this->uptoolnum='';
    
            $this->gold=0;
            $this->diamond=0;
            $this->fishgold=0;
            $this->toolid='';
            $this->toolnum='';
            /**
             *  升级礼包
             */
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->uptoolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->uptoolnum = $re['toolNum'];
                    }
                    $this->upgold=$re['gold'];
                    $this->updiamond=$re['diamond'];
                    $this->upfishgold=$re['fishgold'];
                }else{
                    return $this->addError('gift',$getGift->message);
                }
            }
            /**
             * 每日礼包
             */
            if ($this->types) {
                $getGift = new getgift();
                $data=[];
                foreach ($this->types as $key=>&$value){
                    $data[str_replace('9','',$key)]=$value;
                }
                $re = $getGift->disposeGift($data);
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
            $this->almsrate= $this->almsrate*100;
            $this->killrate= $this->killrate*100;
            return $this->save();
        }
    }
}
