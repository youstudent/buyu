<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%batterylocker}}".
 *
 * @property integer $id
 * @property integer $power
 * @property integer $needtype
 * @property integer $neednumber
 * @property integer $sendgold
 * @property integer $senddiamond
 * @property integer $sendfishgold
 * @property string $sendtoolId
 * @property string $sendtoolNum
 */
class Batterylocker extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%batterylocker}}';
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
            [['power'],'unique'],
            [['power', 'neednumber'], 'required'],
            [['power', 'needtype', 'neednumber', 'sendgold', 'senddiamond', 'sendfishgold'], 'integer'],
            [['power','neednumber'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'数量必须大于0'],
            [['sendtoolId', 'sendtoolNum'], 'string', 'max' => 255],
            [['type','gift','sendgold', 'senddiamond', 'sendfishgold'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'power' => '炮台倍数',
            'needtype' => '类型',
            'neednumber' => '所需钻石',
            'sendgold' => 'Sendgold',
            'senddiamond' => 'Senddiamond',
            'sendfishgold' => 'Sendfishgold',
            'sendtoolId' => 'Sendtool ID',
            'sendtoolNum' => 'Sendtool Num',
            'gift' => '礼包',
        ];
    }
    
    /**
     * 添加充值货币
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->sendtoolId = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->sendtoolNum = $re['toolNum'];
                    }
                    $this->sendgold=$re['gold'];
                    $this->senddiamond=$re['diamond'];
                    $this->sendfishgold=$re['fishgold'];
                }else{
                    return $this->addError('gift',$getGift->message);
                }
            }
            return $this->save();
        }
    }
    
    
    /**
     * 修改
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $this->sendgold=0;
            $this->senddiamond=0;
            $this->sendfishgold=0;
            $this->sendtoolId='';
            $this->sendtoolNum='';
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->sendtoolId = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->sendtoolNum = $re['toolNum'];
                    }
                    $this->sendgold=$re['gold'];
                    $this->senddiamond=$re['diamond'];
                    $this->sendfishgold=$re['fishgold'];
                }else{
                    return $this->addError('gift',$getGift->message);
                }
            }
            return $this->save();
        }
    }
}
