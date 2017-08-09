<?php

namespace common\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%robot}}".
 *
 * @property string $id
 * @property integer $robot_id
 * @property integer $gold
 * @property integer $diamond
 * @property integer $fish_gold
 * @property integer $game_count
 * @property string $robot_win_rate
 * @property integer $created_at
 */
class Robot extends \yii\db\ActiveRecord
{
    public static $status_option=[1=>'正常',0=>'禁用'];
    /**
     * 搜索时使用的用于记住筛选
     * @var string
     */
    public $select  = '';
    
    /**
     * 搜索时使用的用于记住关键字
     * @var string
     */
    public $keyword = '';
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $starttime     = '';
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $endtime      = 0;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%robot}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['name'],'unique'],
            [['robot_id','gold', 'diamond', 'fish_gold', 'game_count', 'created_at','status'], 'integer'],
            [['robot_win_rate'], 'string', 'max' => 20],
            [['gold','diamond','fish_gold','robot_win_rate'],'match','pattern'=>'/^$|^\+?[0-9]\d*$/','message'=>'数量不能是负数'],
            [['select','keyword','starttime','endtime','vip_grade','experience_grade'],'safe'],
            [['robot_win_rate'],'rate']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'robot_id' => '机器人ID',
            'gold' => '金币',
            'diamond' => '钻石',
            'fish_gold' => '鱼币',
            'game_count' => '游戏总局数',
            'robot_win_rate' => '机器人命中',
            'created_at' => '创建时间',
            'status' => '状态',
            'name' => '昵称',
            'vip_grade' => 'Vip等级',
            'experience_grade' => '经验等级',
        ];
    }
    
    
    /**
     * 搜索并分页显示机器人的数据
     * @return array
     */
    public function getList($data = [])
    {
        
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=','created_at',strtotime($this->starttime)])
            ->andWhere(['<=','created_at',strtotime($this->endtime)]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data  = $model->limit($pages->limit)->offset($pages->offset)->orderBy('created_at ASC')->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    
    /**
     * 搜索处理数据函数
     * @return array
     */
    private function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'robot_id'){
                return ['robot_id'=>$this->keyword];
            }
        }
        return [];
    }
    
    
    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if($this->starttime == '') {
//            $this->starttime = date('Y-m-d H:i:s',strtotime('-1 month'));
            $this->starttime = \Yii::$app->params['startTime'];//"2017-01-01 00:00:00";//date('Y-m-d H:i:s',strtotime('-1 month'));
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    
    /**
     *  添加机器人
     */
    public function add($data=[]){
        if($this->load($data) && $this->validate())
        {
           
           $this->created_at=time();
           $this->game_count=0;
           $this->robot_id=date('YmdHis');
           return $this->save();
           
        }
        
    }
    
    /**
     * 修改机器人
     * @param array $data
     */
    
    public function edit($data=[]){
        if($this->load($data) && $this->validate())
        {
           return $this->save();
        }
    }
    
    
    /**
     *  获取 vip等级
     */
    public static function GetVip(){
        $data = VipUpdate::find()->select('grade')->asArray()->all();
        $datas =  ArrayHelper::map($data,'grade','grade');
        return $datas;
    }
    
    /**
     *  获取经验等级
     */
    public static function GetEx(){
        $data = Experience::find()->select('grade')->asArray()->all();
        $datas = ArrayHelper::map($data,'grade','grade');
        return $datas;
    }
    
    
    /**
     *  自定义验证 机器机器人胜率
     */
    public function rate($attribute, $params){
        if ($this->robot_win_rate>100){
            return $this->addError('robot_win_rate','机器人胜率在0-100之间');
        }
    }
    
}
