<?php

namespace backend\models;

use common\helps\getgift;
use Yii;

/**
 * This is the model class for table "{{%fishtask}}".
 *
 * @property integer $id
 * @property integer $fishid
 * @property integer $fishnum
 * @property double $rate
 * @property string $fromfish
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property string $toolnum
 * @property integer $enable
 */
class Fishtask extends \yii\db\ActiveRecord
{
    
    
    public $type;
    
    public $from;
    
    public $gift;
    
    public $types;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fishtask}}';
    }

    /**
     *
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
            [['fishnum','rate'], 'required'],
            [['fishid','fishnum','gold','diamond','fishgold','enable'],'integer'],
            [['rate'],'number'],
            [['fishnum','rate'],'value'],
            [['toolid','toolnum'],'string','max' => 255],
            [['gift','type','gold','diamond','fishgold','enable','fromfish','from','types'],'safe']
        ];
    }
    
    public function value(){
        if ($this->fishnum<0 || $this->rate<0.01 || $this->rate>100){
            $this->addError('fishnum','数量无效');
        }
        
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fishid' => '绑定任务',
            'fishnum' => '数量',
            'rate' => '任务出现概率',
            'fromfish' => '任务鱼',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'toolnum' => 'Toolnum',
            'enable' => 'Enable',
            'gift' => '礼包',
            'type' => '鱼类型',
            'from' => '鱼类型',
        ];
    }
    
    
    
    public static function GetFishtype($id){
        $data =  Fish::findOne(['id'=>$id]);
        if ($data){
            return $data->fishtype;
        }
        return '';
    }
    
    
    
    
    public static function GetFishfrom($c){
        $data =  Fish::findOne(['id'=>$c[0]]);
        if ($data){
            return $data->fishtype;
        }
        return '';
    }
    
    
    public static function GetFishtypes($id){
        $data = Fish::findOne(['id'=>$id]);
        if($data){
            if ($data->fishtype==1){
                return '小鱼';
            }
            if ($data->fishtype==2){
                return '中鱼';
            }
            if ($data->fishtype==3){
                return '金鱼';
            }
            if ($data->fishtype==4){
                return '大鱼';
            }
            if ($data->fishtype==5){
                return 'BOOS';
            }
        }
    }
    
    
    public static function fromfishing($id){
        $data   =  Fish::findOne(['id'=>$id]);
        if ($data){
            return $data->name;
        }
        return '';
    }
    
    
    /**
     * 添加 捕鱼任务
     * @param array $data
     * @return bool
     */
    public function add($data=[]){
        if ($this->load($data) && $this->validate()){
            if ($this->types) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->types);
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
            $this->rate=$this->rate*100;
            return $this->save(false);
        }
    }
    
    /**
     * 修改 捕鱼任务
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
            if ($this->types) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->types);
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
            $this->rate=$this->rate*100;
            return $this->save(false);
        }
    }
    
    
    
    
}
