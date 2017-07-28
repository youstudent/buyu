<?php

namespace common\models;

use common\services\Request;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%fishing}}".
 *
 * @property integer $id
 * @property string $name
 */
class Fishing extends \yii\db\ActiveRecord
{
    public static $give_type = [1=>'小鱼',2=>'中鱼',3=>'大鱼',4=>'金鱼',5=>'BOSS'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fishing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','rate','groupNum','groupNum','cost','ariseRate','ex','aliveTime'], 'integer'],
            [['rate','groupNum','groupNum','cost','ariseRate','ex'],'match','pattern'=>'/^$|^\+?[1-9]\d*$/','message'=>'修改数据必须大于0'],
            [['name'], 'string', 'max' => 255],
            [['updated_at','type'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '鱼名字',
            'type' => '类型',
            'updated_at' => '修改时间',
            'rate' => '击杀概率',
            'groupNum' => '鱼群数',
            'aliveTime' => '存活时间',
            'cost' => '价值',
            'ariseRate' => '出现概率',
            'ex' => '经验',
        ];
    }
    
    
    /**
     *  获取鱼名字
     */
    public static function GetFishing(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getFishes';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        }
    
        $new = $d[0];
        Fishing::deleteAll();
        $model =  new Fishing();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->id=$attributes->id;  //ID
            $model->name =$attributes->name;   //鱼名字
            $model->type =$attributes->type;   //类型
            $model->rate =$attributes->rate;   //击杀概率
            $model->ex =$attributes->ex;   //经验
            $model->groupNum =$attributes->groupNum;   //鱼群数量
            $model->aliveTime =$attributes->aliveTime;   // 存活时间
            $model->cost =$attributes->cost;   // 价值
            $model->ariseRate =$attributes->ariseRate;   // 出现概率
            $model->updated_at =time();   //同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
        
    }
    
    
    /**
     *  鱼群 =值
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $data=[];
            $data['id']=$this->id;
            $data['type']=$this->type;  //  类型
            $data['rate']=$this->rate;  //  击杀概率
            $data['ex']=$this->ex; //   经验值
            $data['groupNum']=$this->groupNum;  // 鱼群数量
            $data['aliveTime']=$this->aliveTime;  //存活时间
            $data['cost']=$this->cost;  // 价值
            $data['ariseRate']=$this->ariseRate;  //出现概率
            $payss = Json::encode($data);
            /**
             * 请求游戏服务端   修改数据
             */
            $url = \Yii::$app->params['Api'].'/gameserver/control/updateFish';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->updated_at=time();
                $this->save(false);
                return true;
            }
            /*$this->give_gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);*/
        }
    }
}
