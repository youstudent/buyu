<?php

namespace backend\models;

use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property integer $gold
 * @property integer $fishgold
 * @property integer $diamond
 * @property string $version
 * @property string $serverid
 * @property integer $sayworldcost
 * @property integer $leavemessagecost
 * @property integer $trident
 * @property integer $shotspeed
 * @property integer $diamondrate
 * @property integer $unlockfishpower
 * @property integer $goldrate
 * @property integer $fishgoldrate
 * @property integer $minrate
 * @property integer $maxrate
 * @property integer $useredpacket
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @return
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
            [['gold', 'fishgold', 'diamond', 'sayworldcost', 'leavemessagecost', 'trident', 'shotspeed', 'diamondrate', 'unlockfishpower', 'goldrate', 'fishgoldrate', 'minrate', 'maxrate', 'useredpacket','tip'], 'number'],
            [[ 'sayworldcost','leavemessagecost', 'trident', 'shotspeed', 'diamondrate', 'unlockfishpower', 'goldrate', 'fishgoldrate', 'minrate', 'maxrate', 'useredpacket','tip'], 'required'],
            [['version', 'serverid'], 'string', 'max' => 255],
            [['maxrate', 'minrate','fishgoldrate','goldrate'], 'validateEdit', 'on' => 'edit'],
            [['gold','fishgold','diamond','sayworldcost','leavemessagecost','trident','shotspeed','unlockfishpower','tip'], 'validateNumedit', 'on' => 'numedit'],
            
        ];
    }
    
    /**
     *  基础数据配置
     */
    public function validateEdit(){
        if ($this->maxrate>100 || $this->minrate>100 ){
            $this->addError('fishgoldrate','所有比例配置不能大于100');
        }
        if ($this->maxrate<=$this->minrate){
            $this->addError('minrate','玩家最小命中率必须小于最大命中率');
        }
        if ($this->maxrate<0.01 || $this->minrate<0.01){
            $this->addError('玩家命中率不能小于0.01');
        }
        if ($this->fishgoldrate<=0 || $this->goldrate<=0){
            $this->addError('金币宝石兑换比例必须大于0');
        }
        
    }
    
    /**
     *  基础数据配置
     */
    public function validateNumedit(){
        if ($this->gold<0 || $this->fishgold<0 ||$this->diamond<0 || $this->sayworldcost<0 ||$this->leavemessagecost<0 || $this->trident<0 ||$this->shotspeed<0 || $this->unlockfishpower<0 || $this->tip<0){
            $this->addError('fishgoldrate','所有数量配置不能小于0');
        }
        
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gold' => '注册账号赠送金币',
            'fishgold' => '注册账号赠送宝石',
            'diamond' => '注册账号赠送钻石',
            'version' => 'Version',
            'serverid' => 'Serverid',
            'sayworldcost' => '世界喇叭所需钻石',
            'leavemessagecost' => '留言板消耗钻石',
            'trident' => '发动三叉戟的能量值',
            'shotspeed' => '发射子弹数  1秒发送个数',
            'unlockfishpower' => '锁宝石市场炮倍所需宝石比例',
            'goldrate' => '兑换金币所需钻石比例',
            'fishgoldrate' => '兑换宝石所需钻石比例',
            'minrate' => '玩家最小命中率',
            'maxrate' => '玩家最大命中率',
            'tip' => '金币数量',
        ];
    }
    
    
    /**
     * 修改 比例
     * @param $data
     * @return bool
     */
    public function edit($data){
        $this->scenario = 'edit';
       if($this->load($data) && $this->validate()){
           $this->minrate =$this->minrate*100;
           $this->maxrate =$this->maxrate*100;
           return  $this->save(false);
       }
    }
    
    
    
    /**
     * 修改 数量
     * @param $data
     * @return bool
     */
    public function numedit($data){
        $this->scenario = 'numedit';
        if ($this->load($data) && $this->validate()){
            return  $this->save(false);
        }
    }
}
