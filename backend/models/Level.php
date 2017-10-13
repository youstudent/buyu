<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%level}}".
 *
 * @property integer $id
 * @property integer $level
 * @property string $ex
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property string $toolnum
 */
class Level extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%level}}';
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
            [['level','ex'], 'required'],
            [['level','ex'], 'number'],
            [['toolid', 'toolnum'], 'string', 'max' => 255],
            [['level','ex'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['gift','type'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => '经验等级',
            'ex' => '所需经验',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'toolnum' => 'Toolnum',
            'gift' => '礼包',
        ];
    }
    
    
    //获取经验等级
    public function getGrade(){
        $level = 1;
        $level = Level::find()->select('level')->orderBy('level DESC')->one();
        if ($level){
            $level = $level->level;
        }
        return $level+1;
    }
    
    /**
     * @param $m
     * @return int
     */
    public static function ex($m){
        $num = ((($m - 1) * ($m - 1) * ($m - 1) + 20) / 5 * (($m - 1) * 2 + 20) + 30);
        return (int)($num / 30) * 30;
    }
    
    /**
     * 添加 经验等级
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
     * 修改 经验等级
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
