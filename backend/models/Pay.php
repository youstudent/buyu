<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%pay}}".
 *
 * @property integer $id
 * @property string $money
 * @property integer $firstdouble
 * @property string $senddiamond
 * @property string $sendgold
 * @property string $sendfishgold
 * @property string $sendtoolid
 * @property string $sendtoolnum
 * @property string $diamond
 * @property string $content
 */
class Pay extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay}}';
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
            [['money'],'unique'],
            [['money', 'firstdouble',  'diamond'], 'required'],
            [['money', 'firstdouble', 'senddiamond', 'sendgold', 'sendfishgold', 'diamond'], 'integer'],
            [['sendtoolid', 'sendtoolnum', 'content'], 'string', 'max' => 255],
            [['gift','type','senddiamond', 'sendgold','sendfishgold'],'safe'],
            [['money','firstdouble','diamond'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'数量必须大于0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => '人民币',
            'firstdouble' => '首冲翻倍',
            'senddiamond' => '赠送钻石',
            'sendgold' => '赠送金币',
            'sendfishgold' => '赠送宝石',
            'sendtoolid' => '道具ID',
            'sendtoolnum' => '道具数量',
            'diamond' => '获得钻石',
            'content' => '备注',
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
            if (mb_strlen($this->content,'UTF8')>9){
                return $this->addError('content','备注字数长度小于9');
            }
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->sendtoolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->sendtoolnum = $re['toolNum'];
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
            $this->sendtoolid='';
            $this->sendtoolnum='';
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->sendtoolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->sendtoolnum = $re['toolNum'];
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
