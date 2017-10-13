<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%signprize}}".
 *
 * @property integer $id
 * @property integer $day
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property integer $batteryid
 * @property integer $level
 * @property string $toolnum
 */
class Signprize extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    
    public static $get_type=[1=>'首次',2=>'循环'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%signprize}}';
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
            [['gift','type'],'safe'],
            [['day', 'gold', 'diamond', 'fishgold', 'batteryid', 'level'], 'integer'],
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
            'day' => '天数',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'batteryid' => '赠送炮台',
            'level' => '类型',
            'toolnum' => 'Toolnum',
            'gift' => '礼包',
        ];
    }
    
    /**
     * 添加 每日签到
     * @param array $data
     * @return bool
     */
    public function add($data=[]){
        if ($this->load($data) && $this->validate()){
            $day =  Signprize::find()->select(['day'])->where(['level'=>$this->level])->orderBy('day DESC')->limit(1)->one();
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $days =1;
            if ($day){
                $days = $day->day+1;
            }
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
            $this->day=$days;
            return $this->save();
        }
    }
    
    /**
     * 修改 每日签到
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
