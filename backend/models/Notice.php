<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use common\models\NoticeObject;
use yii\helpers\ArrayHelper;

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
            [['title','content','status','location'],'required'],
            [['manage_id', 'status', 'time','type'], 'integer'],
            [['content'], 'string'],
            [['manage_name'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['notes', 'location'], 'string', 'max' => 255],
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
           /* if ($this->type == 1 || $this->type == 2) {
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
            $re = Notice::$give;
            foreach ($data as $key=>$v){
        
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                        if (array_key_exists($k,$re)){
                            $vv[$k]=$v2;
                        }
                    }
                }
        
            }
    
            if (empty($vv)){
                $this->addError('give_type','请选择类型');
                return false;
            }
            foreach ($vv as $kk=>$value){
                if (empty($value)){
                    $this->addError('give_type','请选择对应类型的数量');
                    return false;
                }
                if (!is_numeric($value)){
                    $this->addError('give_type','请输入数字类型');
                    return false;
                }
            }
            $prize = json_encode($vv);
            $this->number=$prize;
            $this->manage_id    = \Yii::$app->session->get('manageId');
            $this->manage_name  = \Yii::$app->session->get('manageName');
            $this->time         = time();
            return $this->save();
        }
    }
    
    
    
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $vv =[];
            $re = Notice::$give;
            foreach ($data as $key=>$v){
        
                if (is_array($v)){
                    foreach ($v as $k=>$v2){
                        if (array_key_exists($k,$re)){
                            $vv[$k]=$v2;
                        }
                    }
                }
        
            }
    
            if (empty($vv)){
                $this->addError('give_type','请选择类型');
                return false;
            }
            foreach ($vv as $kk=>$value){
                if (empty($value)){
                    $this->addError('give_type','请选择对应类型的数量');
                    return false;
                }
                if (!is_numeric($value)){
                    $this->addError('give_type','请输入数字类型');
                    return false;
                }
            }
            $prize = json_encode($vv);
            $this->number=$prize;
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