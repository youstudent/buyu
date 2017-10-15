<?php

namespace backend\models;

use common\helps\getgift;
use common\services\Request;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property string $content
 * @property string $createDate
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $toolid
 * @property integer $noticetype
 * @property string $toolNum
 * @property integer $enable
 */
class Notices extends \yii\db\ActiveRecord
{
    public $gift;
    
    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
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
            [['content', 'noticetype'], 'required'],
            [['createDate','type','gift','status'], 'safe'],
            [['gold', 'diamond', 'fishgold', 'noticetype', 'enable'], 'integer'],
            [['content', 'toolid', 'toolNum'], 'string', 'max' => 255],
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
            'createDate' => 'Create Date',
            'gold' => 'Gold',
            'diamond' => 'Diamond',
            'fishgold' => 'Fishgold',
            'toolid' => 'Toolid',
            'noticetype' => '位置',
            'toolNum' => 'Tool Num',
            'enable' => '状态',
            'gift' => '礼包',
        ];
    }
    
    /**
     * 添加 公告
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            if ($this->noticetype == 1 || $this->noticetype == 2){
                if (self::find()->where(['noticetype'=>$this->noticetype,'enable'=>1])->exists()){
                    $this->addError('noticetype','登录公告和大厅公告只能显示一条');
                    return false;
                }
            }
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->toolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->toolNum = $re['toolNum'];
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
    
    public function adds($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            if ($this->noticetype == 1 || $this->noticetype == 2){
                if (self::find()->where(['noticetype'=>$this->noticetype,'enable'=>1])->exists()){
                    $this->addError('noticetype','登录公告和大厅公告只能显示一条');
                    return false;
                }
            }/**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['content']=$this->content;
            $pays['type']=$this->noticetype;
            $pays['useable']=$this->enable;
            $pays['status']=1;
            if ($this->type){
                    foreach ($this->type as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if (empty($VV) || !is_numeric($VV) || $VV<=0){
                                return $this->addError('type','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if (empty($VV)  || !is_numeric($VV)  || $VV<=0){
                                return $this->addError('type','奖品数量无效');
                            }
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
            }
            
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addNotice';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }
        }
    }
    
    /**
     * 修改公告
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
            $this->toolNum='';
            if ($this->type) {
                $getGift = new getgift();
                $re = $getGift->disposeGift($this->type);
                if ($re){
                    if ($re['toolid']) {
                        $this->toolid = $re['toolid'];
                    }
                    if ($re['toolNum']) {
                        $this->toolNum = $re['toolNum'];
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
    
    public function edits($data = []){
        if($this->load($data) && $this->validate())
        {
            /**
             * 接收数据  拼装
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['id']=$this->id;
            $pays['content']=$this->content;
            $pays['type']=$this->noticetype;
            $pays['useable']=$this->enable;
            $pays['status']=1;
            if ($this->type){
                    foreach ($this->type as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if (empty($VV) || !is_numeric($VV) || $VV<=0 ){
                                return $this->addError('type','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if (empty($VV) || !is_numeric($VV) || $VV<=0 ){
                                return $this->addError('type','奖品数量无效');
                            }
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
                
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updateNotice';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }
            
        }
    }
}
