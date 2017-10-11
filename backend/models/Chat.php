<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property integer $id
 * @property string $message
 * @property integer $useable
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
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
            [['message'], 'required'],
            [['useable'], 'integer'],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => '内容',
            'useable' => '状态',
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
}
