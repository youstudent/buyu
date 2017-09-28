<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%experience}}".
 *
 * @property string $id
 * @property integer $grade
 * @property integer $type
 * @property integer $number
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class Experience extends  Object
{
    public static $give;
    public $give_type;
    public static $get_type=[1=>'金币',2=>'钻石'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%experience}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'],'required'],
            [['grade', 'type','manage_id', 'created_at', 'updated_at'], 'integer'],
            [['grade'],'unique'],
            [['grade','type'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量无效'],
            [['manage_name'], 'string', 'max' => 20],
            [['number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade' => '经验等级',
            'type' => '所需经验',
            'number' => '数量',
            'manage_id' => 'Manage ID',
            'manage_name' => 'Manage Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'give_type' => '赠送礼包',
            
        ];
    }
    
    /**
     * 添加一个通知
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if($this->load($data) && $this->validate())
        {
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $pays['ex']=$this->type;
            $pays['level']=$this->grade;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give','奖品数量无效');
                            }
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
           // $pays['send']=$send;
            if (!empty($send)){
                $pays['send']=$send;
            }
            /**
             * 请求服务器地址 炮台倍数
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/addLevel';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->number=Json::encode($send);
                $this->created_at=time();
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
            /**
             * 接收数据  拼装
             */
            $datas=['gold','diamond','fishGold'];
            $pays=[];
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            
            $pays['level']=$this->grade;
            $pays['id']=$this->id;
            $pays['ex']=$this->type;
            foreach ($data as $K=>$v){
                if (is_array($v)){
                    foreach ($v as $kk=>$VV){
                        if (in_array($kk,$datas)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give','奖品数量无效');
                            }
                            $send[$kk]=$VV;
                        }
                        if (is_numeric($kk)){
                            if ($VV<=0 || $VV==null || !is_numeric($VV)){
                                return $this->addError('give','奖品数量无效');
                            }
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
            //$pays['send']=$send;
            /**
             * 请求游戏服务端   修改数据
             */
            $payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/control/updateLevel';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                $this->number=Json::encode($send);
                $this->updated_at=time();
                $this->save(false);
                return true;
            }
            /* $this->gold_num=Json::encode($send);
             $this->manage_id    = \Yii::$app->session->get('manageId');
             $this->manage_name  = \Yii::$app->session->get('manageName');
             $this->updated_at         = time();
             return $this->save(false);*/
        }
    }
    
    
    /**
     * 初始化赠送礼包配置
     */
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
    public static function GetExperience(){
        $url = \Yii::$app->params['Api'].'/control/getLevels';
        $data = \common\services\Request::request_post($url,['time'=>time()]);
        $d=[];
        foreach ($data as $key=>$v){
            if (is_array($v)){
                $d[]=$v;
            }
        }
       
        $new = $d[0];
        Experience::deleteAll();
        $model =  new Experience();
        //请求到数据   循环保存到数据库
        foreach($new as $K=>$attributes)
        {
            $model->number=Json::encode($attributes->send);  //赠送礼包
            $model->id=$attributes->id;
            $model->type =$attributes->ex;   //所需经验
            $model->grade =$attributes->level;  //炮台倍数
            $model->created_at =time();  // 同步时间
            $_model = clone $model;
            $_model->setAttributes($attributes);
            $_model->save(false);
        }
        return $data['code'];
    }
    
    //获取经验等级
    public function getGrade(){
       $grade = 1;
       $grade = Experience::find()->select('grade')->orderBy('grade DESC')->one();
       if ($grade){
           $grade = $grade->grade;
       }
       return $grade+1;
    }
    
    public static function ex($m){
       // floor((($model->grade-1)^3+20)/5*(($model->grade-1)*2+20)+30);
       
    }
}
