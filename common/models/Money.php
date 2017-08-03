<?php

namespace common\models;

use common\services\Request;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%money}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $number
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $updated_at
 */
class Money extends Object
{
    public static $get_type =['1'=>'金币','2'=>'钻石',3=>'发布喇叭所需钻石',4=>'发布留言所需要的钻石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%money}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','detail'], 'required'],
            [['number'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['id', 'type', 'number', 'manage_id', 'updated_at'], 'integer'],
            [['manage_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'number' => '数量',
            'manage_name' => '修改人',
            'manage_id' => '修改人ID',
            'updated_at' => '修改时间',
            'detail' => '说明',
        ];
    }
    
    
    
    //请求游戏服务器  道具列表
    public static function GetMoney(){
        $url = Yii::$app->params['Api'].'/gameserver/control/gettools';
        $data = Request::request_post($url,['time'=>time()]);
        Money::deleteAll();
        $model =  new Money();
        foreach($data as $K=>$attributes)
        {
            $model->id=$attributes->toolId;
            $model->name =$attributes->toolName;
            $model->number =1;
            $model->toolDescript =$attributes->toolDescript;
            $model->jewel_number =$attributes->unitPrice;
            $model->level =$attributes->minVip;
            $model->updated_at =time();
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
    
    /**
     *   修改 货币
     */
    public function edit($data = []){
        /*\Yii::$app->response->format = Response::FORMAT_JSON;
        $model->manage_id    = \Yii::$app->session->get('manageId');
        $model->manage_name  = \Yii::$app->session->get('manageName');
        $model->updated_at=time();*/
        if($this->load($data) && $this->validate()) {
            $arr=[];
            /**
             *  请求服务器修改 货币配置数据
             */
            $JS = Json::encode($arr);
            $url = \Yii::$app->params['Api'].'/gameserver/control/updateEveryDayTask';
            $re =Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                //修改数据
            }
        }
    }
}
