<?php

namespace common\models;

use backend\models\Shop;
use common\services\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%day}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $give_type
 * @property string $day
 * @property integer $gold_num
 * @property integer $jewel_num
 * @property integer $salvo_num
 * @property integer $updated_at
 * @property string $manage_name
 * @property integer $manage_id
 */
class Day extends Object
{
    public $type;
    public static $give;
    public static $get_type=[1=>'一次性使用',2=>'固定使用奖励数值'];
    public static $get_give_type=[1=>'金币',2=>'钻石',3=>'礼炮'];
    public static $get_gives_type=[1=>'金币',2=>'钻石',3=>'礼炮',12=>'金币,钻石',13=>'金币,礼炮',123=>'金币,钻石,礼炮',23=>'钻石,礼炮',''=>''];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%day}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jewel_num', 'salvo_num', 'updated_at', 'manage_id'], 'integer'],
            [['day','manage_name'], 'string', 'max' => 20],
            [['jewel_num','salvo_num'],'match','pattern'=>'/^0$|^\+?[1-9]\d*$/','message'=>'数量不能是负数'],
            [['give_type','type','gold_num'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '赠送类型',
            'give_type' => '赠送类型',
            'day' => '签到天数',
            'gold_num' => '金币数量',
            'jewel_num' => '钻石数量',
            'salvo_num' => '礼炮数量',
            'updated_at' => '修改时间',
            'manage_name' => '修改人',
            'manage_id' => '修改ID',
        ];
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
     * 修改 每日签到管理
     *
     * @param array $data
     * @return bool
     */
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
            $pays['id']=$this->id;
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
            $send['tools']=$tools;
            $pays['send']=$send;
            /**
             * 请求游戏服务端   修改数据
             */
            /*$payss = Json::encode($pays);
            $url = \Yii::$app->params['Api'].'/gameserver/control/updatebatterypower';
            $re = Request::request_post_raw($url,$payss);
            if ($re['code']== 1){
                return true;
            }*/
            $this->gold_num=Json::encode($send);
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->updated_at         = time();
            return $this->save(false);
        }
    }
}
