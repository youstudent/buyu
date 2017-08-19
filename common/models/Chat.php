<?php

namespace common\models;

use common\services\Request;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property string $id
 * @property string $content
 * @property integer $status
 * @property integer $reg_time
 * @property integer $updated_time
 */
class Chat extends Object
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'],'required'],
            [['content','manage_name'], 'string'],
            [['status', 'reg_time','manage_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'status' => '状态',
            'reg_time' => '添加时间',
            'manage_name' => '添加人',
            'manage_id' => '添加人ID',
        ];
    }
    
    /**
     * 添加聊天
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
    
            /**
             * 请求 游戏服务器 聊天设置
             */
            $data=[];
            $data['message']=$this->content;  //内容
            $data['useable']=$this->status;   //状态
            $payss = Json::encode($data);
            $url = \Yii::$app->params['Api'].'/control/addChat';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->reg_time=time();
                $this->save(false);
                return true;
            }
            /*$this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->reg_time         = time();
            return $this->save();*/
        }
    }
    
    
    /**
     * 修改 聊天内容
     *
     * @param array $data
     * @return bool
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $data=[];
            $data['id']=$this->id;
            $data['message']=$this->content;   //内容
            $data['useable']=$this->status;   //状态
            $payss = Json::encode($data);
            /**
             * 请求游戏服务端   修改数据
             */
            $url = \Yii::$app->params['Api'].'/control/updateChat';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->manage_id    = \Yii::$app->session->get('manageId');
                $this->manage_name  = \Yii::$app->session->get('manageName');
                $this->reg_time=time();
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
    
    
    /**
     *    初始化游戏服务端  聊天
     */
    public static function GetChat(){
        $url = \Yii::$app->params['Api'].'/control/getChat';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
            
        }
        $new = $d[0];
        Chat::deleteAll();
        $model =  new Chat();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->id=$attributes->id;
            $model->content =$attributes->message;   // 聊天内容
            $model->status =$attributes->useable;  //  状态
            $model->reg_time =time();  //  状态
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
}
