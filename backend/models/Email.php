<?php

namespace backend\models;

use common\helps\getgift;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $diamond
 * @property string $gold
 * @property string $fishgold
 * @property string $toolid
 * @property string $toolNum
 * @property string $createDate
 */
class Email extends \yii\db\ActiveRecord
{
    public static $give; //赠送类型
    public $type;
    
    public $gift;
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
        return '{{%email}}';
    }

    
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
            [['title', 'content'], 'required'],
            [['diamond', 'gold', 'fishgold'], 'integer'],
            [['createDate','gift','type'], 'safe'],
            [['title', 'content', 'toolid', 'toolNum'], 'string', 'max' => 255],
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
            'content' => '邮件内容',
            'diamond' => '钻石',
            'gold' => '金币',
            'fishgold' => '宝石',
            'toolid' => '道具ID',
            'toolNum' => '道具数据',
            'createDate' => '发布时间',
            'gift' => '礼包',
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
            ->andWhere(['>=', 'createDate', $this->starttime])
            ->andWhere(['<=', 'createDate', $this->endtime])->orderBy('createDate DESC');
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        
        $data = $model->limit($pages->limit)->offset($pages->offset)->all();
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
            else{
                return ['like', 'title', $this->keyword];
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
        if ($this->starttime == '') {
//            $this->starttime = date('Y-m-d H:i:s',strtotime('-1 month'));
            $this->starttime = \Yii::$app->params['startTime'];//"2017-01-01 00:00:00";//date('Y-m-d H:i:s',strtotime('-1 month'));
        }
        if ($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    
    /**
     * 添加邮件
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        if ($this->load($data) && $this->validate()) {
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
    
    // 创建模型自动设置赠送礼品类型
    public function __construct(array $config = [])
    {
        //查询 道具列表中的数据
        $data  = Toolinfo::find()->asArray()->all();
        //将道具数组格式化成  对应的数组
        $new_data = ArrayHelper::map($data,'toolid','toolname');
        //$new_data = ArrayHelper::map($data,'id','name');
        //自定义 赠送类型
        $datas = ['gold'=>'金币','diamond'=>'钻石','fishGold'=>'鱼币'];
        
        //将数据合并 赋值给数组
        self::$give= ArrayHelper::merge($datas,$new_data);
        parent::__construct($config);
    }
}
