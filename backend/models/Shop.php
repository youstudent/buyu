<?php

namespace backend\models;

use common\services\Request;
use Yii;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $number
 * @property integer $jewel_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','jewel_number'],'required'],
            [['number','jewel_number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['number', 'jewel_number', 'created_at', 'updated_at','type','order_number','level'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名',
            'number' => '数量',
            'jewel_number' => '所需钻石',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'type' => '类型',
            'level' => '购买等级',
        ];
    }
    
    //修改商品
    public function edit($data=[]){
        if($this->load($data) && $this->validate()){
            $datas['id']=$this->order_number;
            $datas['num']=$this->number;
            $datas['level']=$this->level;
            $datas['cost']=$this->jewel_number;
            //$json = json_encode($datas);
            //$data = Request::request_post(\Yii::$app->params['ApiUserPay'],['game_id'=>$model->game_id,'gold'=>$this->pay_gold_num,'gold_config'=>GoldConfigObject::getNumCodeByName($this->pay_gold_config)]);
            $result = Request::request_post(Yii::$app->params['Api'].'/gameserver/control/updatetool',$datas);
            if($result['code'] == 1){
                return $this->save();
            }
            return ['code'=>0,'message'=>$result->message];
        }
    }
    
}
