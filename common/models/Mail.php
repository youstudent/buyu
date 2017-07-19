<?php

namespace common\models;

use backend\models\Shop;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%mail}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $type
 * @property integer $number
 * @property integer $yes_no
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $created_at
 */
class Mail extends Object
{
    public static $give; //赠送类型
    /**
     * 搜索时使用的用于记住筛选
     * @var string
     */
    public $select = '';
    
    /**
     * 搜索时使用的用于记住关键字
     * @var string
     */
    public $keyword = '';
    
    /**
     * 用户充值的金币数量
     * @var string
     */
    public $pay_gold_num = 0;
    
    /**
     * 用户充值类型
     * @var string
     */
    public $pay_gold_config = '';
    
    /**
     * 充值时候的金额
     * @var int
     */
    public $pay_money = 0;
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $starttime = '';
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $endtime = 0;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['status', 'type','yes_no', 'manage_id', 'created_at'], 'integer'],
            [['title', 'manage_name'], 'string', 'max' => 20],
            [['select', 'keyword', 'pay_gold_num', 'pay_gold_config'], 'safe'],
            [['starttime', 'endtime','number'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'status' => '状态',
            'type' => '赠送类型',
            'number' => '赠送类型',
            'yes_no' => '是否有奖品',
            'manage_name' => '发布人',
            'manage_id' => '发布人ID',
            'created_at' => '发布时间',
        ];
    }
    
    
    /**
     * 搜索并分页显示用户的数据
     * @return array
     */
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model = self::find()->andWhere($this->searchWhere())
            ->andWhere(['>=', 'created_at', strtotime($this->starttime)])
            ->andWhere(['<=', 'created_at', strtotime($this->endtime)]);
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        
        $data = $model->limit($pages->limit)->offset($pages->offset)->all();
        
        /*foreach ($data as $key=>$value){
            $data[$key]['gold'] = $value->getGold();
        }*/
        
        return ['data' => $data, 'pages' => $pages, 'model' => $this];
    }
    
    
    /**
     * 搜索处理数据函数
     * @return array
     */
    private function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword)) {
            
            if ($this->select == 'title')
                return ['like', 'title', $this->keyword];
        }
        return [];
    }
    
    
    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if ($this->starttime == '') {
//            $this->starttime = date('Y-m-d H:i:s',strtotime('-1 month'));
            $this->starttime = \Yii::$app->params['startTime'];//"2017-01-01 00:00:00";//date('Y-m-d H:i:s',strtotime('-1 month'));
        }
        if ($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * 添加一个通知
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        if ($this->load($data) && $this->validate()) {
            /*if ($this->type && $this->number) {
                $this->yes_no = 1;
            } else {
                $this->yes_no = 0;
            }
            if ($this->type == 1 || $this->type == 2) {
                if (empty($this->number)) {
                    $this->addError('message', '请选择赠送的数量!!');
                    return false;
                }
            }
            if ($this->number && $this->type==0){
                $this->addError('message', '请选择赠送类型!!');
                return false;
            }*/
    
            $vv =[];
            $re = Mail::$give;
            foreach ($data as $key=>$v){
        
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                        if (array_key_exists($k,$re)){
                            $vv[$k]=$v2;
                        }
                    }
                }
        
            }
            //请求游戏服务器地址
            $prize = json_encode($vv);
            $this->number=$prize;
            $this->status = 1;
            $this->yes_no=$prize?1:0;
            $this->manage_id = \Yii::$app->session->get('manageId');
            $this->manage_name = \Yii::$app->session->get('manageName');
            $this->created_at = time();
            return $this->save();
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
   
}
