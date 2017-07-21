<?php

namespace common\models;

use Codeception\Exception\TestRuntimeException;
use common\services\Request;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%get_gold}}".
 *
 * @property string $id
 * @property integer $lowest
 * @property integer $type
 * @property integer $number
 * @property integer $updated_at
 */
class GetGold extends  Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%get_gold}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','lowest'],'required'],
            [['number','lowest','count'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['lowest', 'type', 'number', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lowest' => '最低谷值',
            'type' => '类型',
            'number' => '领取数量',
            'updated_at' => '更新时间',
            'count' => '每日领取次数',
        ];
    }
    
    
    /**
     * 修改  救济 数值
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            return true;
            $data=[];
            $data['id']=$this->id;
            $data['number']=$this->number;  //数量
            $data['type']=$this->type;  // 类型
            $data['lowest']=$this->lowest; //最低固值
            $data['count']=$this->count;  //每日领取次数
            $payss = Json::encode($data);
            /**
             * 请求游戏服务端   修改数据
             */
            $url = \Yii::$app->params['Api'].'/gameserver/control/updatebatterypower';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }
            /*$this->give_gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);*/
        }
    }
    
    /**
     * 获取游戏服务端,救济金数据
     */
    public static function GetGold(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getbatterypower';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        
        GetGold::deleteAll();
        $model =  new GetGold();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            // $model->give_gold_num=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            $model->type =$attributes->content;   // 类型
            $model->lowest =$attributes->status;  // 最低值
            $model->number =$attributes->number;  // 领取数量
            $model->count =$attributes->count;  // 每日领取次数
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
    }
}
