<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use common\models\NoticeObject;
use common\services\Request;
use Symfony\Component\DomCrawler\Field\InputFormField;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Notice extends NoticeObject
{
    public static $give;
    public $get_type;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content','status','location'],'required'],
            [['manage_id', 'status', 'time','type'], 'integer'],
            [['content'], 'string'],
            [['manage_name'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['notes', 'location'], 'string', 'max' => 255],
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
            if ($this->location == 1 || $this->location == 2){
              if (Notice::find()->where(['location'=>$this->location,'status'=>1])->exists()){
                  $this->addError('status','登录公告和大厅公告只能显示一条');
                  return false;
              }
            }
           // Notice::findOne(['location'=>$this->location,'status'=>1]);
            /**
             *  将接收到的数据进行 拼装发送给游戏服务器
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['content']=$this->content;
            $pays['type']=$this->location;
            $pays['useable']=$this->status;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
                    }
                }
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            if (!empty($send)){
                $pays['send']=$send;
            }
          /*  $send['tools']=$tools;
            $pays['send']=$send;*/
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/addNotice';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->number=Json::encode($send);
                $this->time        = time();
                $this->save(false);
                return true;
            }
            /*;
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save();*/
        }
    }
    
    
    
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            /*if ( ($this->location == 1 || $this->location == 2) && $this->status ==1 ){
                if (Notice::find()->where(['location'=>$this->location,'status'=>1])->exists()){
                    $this->addError('status','登录公告和大厅公告只能显示一条');
                    return false;
                }
            }*/
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
            $pays['type']=$this->location;
            $pays['useable']=$this->status;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            $tool['toolId']=$kk;
                            $tool['toolNum']=$VV;
                            $tools[$i]=$tool;
                            $i++;
                        }
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
            $url = \Yii::$app->params['Api'].'/gameserver/control/updateNotice';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->number=Json::encode($send);
                $this->time= time();
                $this->save(false);
                return true;
            }
            
        }
    }
    
    
    // 创建模型自动设置赠送礼品类型
    public function __construct(array $config = [])
    {
        //查询 道具列表中的数据
        $data  = Shop::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data,'id','name');
        //自定义 赠送类型
        $datas = ['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
        //将数据合并 赋值给数组
        self::$give= ArrayHelper::merge($datas,$new_data);
        parent::__construct($config);
    }
    
    
    /**
     *    初始化游戏服务端 炮台倍数
     */
    public static function GetNotice(){
        $url = \Yii::$app->params['Api'].'/gameserver/control/getNotice';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        }
        $new = $d[0];
        //Notice::deleteAll();
        
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $data = Notice::findOne(['id'=>$attributes->id]);
            if ($data){
                $data->content =$attributes->content;  //  公告内容
                $data->status =$attributes->useable;  //  公告状态
                $data->location =$attributes->type;  //  公告位置
                $data->save();
            }else{
                $model =  new Notice();
                $model->id=$attributes->id;
                $model->content =$attributes->content;  //  公告内容
                $model->status =$attributes->useable;  //  公告状态
                $model->location =$attributes->type;  //  公告位置
                $model->time =time();  //  同步时间
                $model->save();
            }
          /*  $model->number=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            $model->content =$attributes->content;  //  公告内容
            $model->status =$attributes->useable;  //  公告状态
            $model->location =$attributes->type;  //  公告位置
            $model->time =time();  //  同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);*/
        }
        return 1;
    }
}