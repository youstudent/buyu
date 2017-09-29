<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/7/30
 * Time: 09:18
 */

namespace backend\models;


use backend\controllers\DayController;
use common\models\DayList;
use common\models\DayTask;
use common\services\Request;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ViewAction;

class LandForm extends Model
{
    public static $give;
    public $type;
    public $id;
    public $gives;
    public $typeId;
    public $enable;
    public $num;
    public $description;
    public static $enables=[1=>'开启',0=>'关闭'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['give','type','id','gives','typeId','enable','num','description'],'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gives' => '礼包',
            'type' => '奖励',
            'types' => '礼包',
            'num' => '次数',
            'enable' => '状态',
            'description' => '描述',
        ];
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
     * @param array $data
     * @return bool
     *  修改每日任务配置礼物包
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $arr = [];
            $content = \common\models\Test::set($this->type);
            if (!$content){
                return $this->addError('types','数量无效');
            }
            $arr['enable']=$this->enable;
            $arr['id']=$this->id;
            $arr['typeId']=$this->typeId;
            $arr['content']=$content;
            $arr['description']=$this->description;
            $JS = Json::encode($arr);
            $url = \Yii::$app->params['Api'].'/control/updateEveryDayTask';
            $re = Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                $row= DayList::findOne(['type_id'=>$this->typeId]);
                $rows = DayTask::findOne(['type_id'=>$this->typeId]);
                $rows->status =$this->enable;
                $rows->description =$this->description;
                $rows->content =Json::encode($content);
                $rows->save(false);
                $row->status=$this->enable;
                $row->description=$this->description;
                $row->content=Json::encode($content);
                $row->save(false);
                return true;
            }
            
        }
    }
    
    
    
    
    /**
     * @param array $data
     * @return bool
     *    配置礼物包和次数
     */
    public function editnum($data = []){
        if($this->load($data) && $this->validate())
        {
            $arr = [];
            $content['num']=$this->num;
            $send=[];
            $tools = [];
            $i = 0;
            $tool = [];
            $datas=['gold','diamond','fishGold'];
            if ($this->type){
                foreach ($this->type as $key => $value) {
                    if (in_array($key,$datas)) {
                        if ($value<=0 || $value==null || !is_numeric($value)){
                            return $this->addError('types','数量无效');
                        }
                        $send[$key] = $value;
                    }
                    if (is_numeric($key)) {
                        if ($value<=0 || $value==null || !is_numeric($value)){
                            return $this->addError('types','数量无效');
                        }
                        $tool['toolId'] = $key;
                        $tool['toolNum'] = $value;
                        $tools[$i] = $tool;
                        $i++;
                    }
                }
                
            }
            if (!empty($tools)){
                $send['tools']=$tools;
            }
            $sends=['gold'=>0,'diamond'=>0,'fishGold'=>0];
            if (empty($send)){
                $content['send']=$sends;
            }else{
                $content['send']=$send;
            }
            //$content['send']=$send;
            $arr['enable']=$this->enable;
            $arr['id']=$this->id;
            $arr['typeId']=$this->typeId;
            $arr['content']=$content;
            $arr['description']=$this->description;
            $JS = Json::encode($arr);
            $url = \Yii::$app->params['Api'].'/control/updateEveryDayTask';
            $re = Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                $re= DayList::findOne(['type_id'=>$this->typeId]);
                $re->status=$this->enable;
                $re->description=$this->description;
                $re->content=Json::encode($content);
                $re->save(false);
                return true;
            }
            
        }
    }
    
}