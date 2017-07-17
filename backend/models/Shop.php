<?php

namespace backend\models;

use common\models\Test;
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
            [['jewel_number'],'required'],
            [['jewel_number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['number','jewel_number','created_at','type','order_number','level'], 'integer'],
            [['name'],'string', 'max' => 20],
            
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
            'toolDescript' => '描述',
        ];
    }
    
    //修改商品
    public function edit($data=[]){
        if($this->load($data,'') && $this->validate()){
            $datas['id']=$this->id;
            $datas['num']=1;
            $datas['level']=$this->level;
            $datas['cost']=$this->jewel_number;
            //$data = Request::request_post(\Yii::$app->params['ApiUserPay'],['game_id'=>$model->game_id,'gold'=>$this->pay_gold_num,'gold_config'=>GoldConfigObject::getNumCodeByName($this->pay_gold_config)]);
            $result = Request::request_post(Yii::$app->params['Api'].'/gameserver/control/updatetool',$datas);
            if($result['code'] == 1){
                $this->updated_at=time();
                return $this->save(false);
            }
            return ['code'=>0,'message'=>$result['message']];
        }
    }
    
    
    //请求游戏服务器  道具列表
    public static function GetShop(){
        $url = Yii::$app->params['Api'].'/gameserver/control/gettools';
         $data = Request::request_post($url,['time'=>time()]);
         $d=[];
         foreach ($data as $key=>$v){
             
             if (is_object($v)){
                 $d[]=$v;
             }
             
         }
         $new = $d[0]->tools;
         foreach ($new as &$e){
             $e->toolName;
             
         }
         
         /*foreach ($new as $K=>$value){
             $model = new Shop();
             $model->save($value);
         }*/
        Shop::deleteAll();
        $model =  new Shop();
        foreach($new as $K=>$attributes)
        {
            $model->id=$attributes->toolId;
            $model->name =$attributes->toolName;
            $model->number =1;
            $model->toolDescript =$attributes->toolDescript;
            $model->jewel_number =$attributes->unitPrice;
            $model->level =$attributes->minVip;
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
    }
    
}