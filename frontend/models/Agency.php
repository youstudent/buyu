<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace frontend\models;

use common\models\AgencyObject;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class Agency extends AgencyObject{

    /**
     * 重复密码
     * @var string
     */
    public $reppassword = '';

    /**
     * 推荐码
     * @var string
     */
    public $recode = '';

    /**
     * 用户旧密码
     * @var string
     */
    public $used_password   = '';

    /**用户新密码
     * @var string
     */
    public $new_password    = '';

    /**用户重复新密码
     * @var string
     */
    public $repeat_password = '';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['password'],'min'=>6],
            [['phone','password'],'required','on'=>'login'],
            [['phone','password','reppassword','name','identity'],'required','on'=>'add'],
            [['pid', 'reg_time',  'diamond', 'status', 'fishGold'], 'integer'],
            [['phone'], 'string', 'max' => 12],
            [['phone'],'unique','on'=>'add'],
            [['password'], 'string', 'max' => 64],
            [['name', 'identity'], 'string', 'max' => 32],
            [['gold','select','keyword'],'safe'],
            [['reppassword'],'vlidatePassword','on'=>'add'],
            [['phone'],'match','pattern'=>'/^((13[0-9])|(15[^4])|(18[0,2,3,5-9])|(17[0-8])|(147))\\d{8}$/'],
//            [['identity'],'match','pattern'=>'/(^\d{15}$)|(^\d{6}[1|2]\d{10}(\d|X|x)$)/'],
            [['identity'],'match','pattern'=>'/(^\d{15}$)|\d{6}(18|19|20)\d{2}(0[1-9]|1[1-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])\d{3}(\d{1}|X)$/'],
            [['recode'],'validateCodeExist','on'=>'add'],
            [['used_password','new_password','repeat_password'],'required','on'=>['editPassword','editSave']],
            [['used_password'],'validatePassword','on'=>'editPassword'],
            [['repeat_password'],'validateRepeat','on'=>'editPassword'],
        ];
    }

    /**
     * 判断原来密码是否正确
     * @param $a
     * @param $p
     */
    public function validatePassword($a,$p)
    {
        if(!$this->hasErrors())
        {
            if ($this->used_password !==  $this->password)
            {
                return $this->addError($a,'原密码不正确');
            }
        }
    }

    /**
     * 判断两次密码是否一致
     * @param $a
     * @param $p
     */
    public function validateRepeat($a,$p)
    {
        if(!$this->hasErrors())
        {
            if($this->new_password != $this->repeat_password)
            {
                return $this->addError($a,'两次密码不一致');
            }
        }
    }


    /**
     * 判断recode 推荐码用户是否存在
     * @param $attribute
     * @param $params
     * @return mixed|void|string
     */
    public function validateCodeExist($attribute ,$params)
    {
        if(!$this->hasErrors() && !empty($this->recode))
        {
            $data = self::find()
                    ->where(['code'=>$this->recode])
                    ->select(['id'])
                    ->asArray()
                    ->one();
            if($data){
                return $this->pid = $data['id'];
            }
            return $this->addError($attribute,'推荐码用户不存在');
        }
    }

    public function vlidatePassword($attr,$pasm)
    {
        if(!$this->hasErrors())
        {
            if($this->password != $this->reppassword)
            {
                return $this>$this->addError($attr,'两次密码不一致');
            }
        }
    }

    public function attributeLabels()
    {
        $arr = [
            'recode'    =>'推荐码',
            'reppassword'=>'重复密码',
            'used_password'=>"原密码",
            'new_password'=>"新密码",
            'repeat_password'=>"确认密码",
            'phone'         =>'手机号',
        ];
        return ArrayHelper::merge(parent::attributeLabels(),$arr);
    }

    /**
     * 修改密码操作
     * @param $data
     * @return bool
     */
    public function editPassword($data)
    {
        $this->scenario = 'editPassword';
        if($this->load($data) && $this->validate())
        {
            $this->scenario = 'editSave';
            $this->password = $this->new_password;
            return $this->save();
        }
    }
    /**
     * 获取用户金币余额
     * @return string

    public function getGold()
    {
        $gold = self::findOne(\Yii::$app->session->get('agencyId'));
        return $gold->gold;
    }*/

    /**
     * 用户注册操作
     * @param array $data
     * @return bool
     */
    public function add($data= [])
    {
        $this->scenario = 'add';
        if($this->load($data) && $this->validate())
        {
            $this->reg_time = time();
            $this->gold     = 0;
            $this->gold_all = 0;
            $this->status   = 3;
            $this->code =$this->createCode();
            if($this->save()) {
                \Yii::$app->session->set('agencyId',$this->id);
                \Yii::$app->session->set('agencyName',$this->name);
                \Yii::$app->session->set('status',$this->status);
                return true;
            }
        }
    }
    /**
     * 用户登录操作
     * @param array $data
     * @return bool
     */
    public function login($data =[]){

        $this->scenario = 'login';
        if($this->load($data) && $this->validate())
        {
            $data = self::find()->where(["phone"=>$this->phone])->one();
            if(empty($data))
                $this->addError('phone','手机号不存在');
            elseif ($data->password != $this->password)
                $this->addError('password','密码错误');
            else
            {
                \Yii::$app->session->set('agencyId',$data->id);
                \Yii::$app->session->set('agencyName',$data->name);
                \Yii::$app->session->set('status',$data->status);

                return $data->status;
            }
        }
    }

    /**
     * 获取萌萌萌下的三级代理分销用户
     * @param $id
     * @param $param
     * @return array
     */
    public function getDistributionAll($param=[])
    {
        $this->load($param);
        $inArrays = $this->GetAgency([\Yii::$app->session->get('agencyId')]);
        $inArray  = [];
        foreach ($inArrays as $key=>$value)
        {
            foreach ($value as $item)
            {

                $inArray[] = $item;
            }
        }

        $model = self::find()->where(['in','id',$inArray]);
        $pages = new Pagination(['totalCount'=>$model->count(),'pageSize'=>\Yii::$app->params['pageSize']]);
        $data  = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();

        foreach ($data as $key=>$value)
        {
            foreach ($inArray as $item)
            {
                if($item['id'] == $value['id'])
                {
                    $retGold = 0;
                    $datas = Rebate::find()
                            ->andWhere(["agency_pay_user_id"=>$value['id']])
                            ->andWhere(["agency_id"=>\Yii::$app->session->get('agencyId')])
                            ->select(['gold_num'])
                            ->all();
                    foreach ($datas as $keys=>$values)
                    {
                        $retGold+=$values['gold_num'];
                    }
                    $data[$key]['fl_gold_num'] = $retGold;
                    $data[$key]['dj']          = $item['dj'];
                }
            }
        }

        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }

    /**
     * 获取三级分销的id 并返回一个数组
     * @param $id
     * @param int $i
     * @return array
     */
    public function GetAgency($id,$i=1)
    {
        static  $inArrays = [];
        $inArray          = [];
        $retArry          = [];
        if(empty($id) || $i>3) return $inArrays;
        $data = self::find()->where(['in','pid',$id])
                ->andWhere($this->searchWhere())
                ->select(['id'])
                ->asArray()
                ->all();
        if(empty($data)) return $this->GetAgency([],++$i);;
        foreach ($data as $key=>$value)
        {
            if($i == 1) {
                $value['dj'] = '一级代理';
            }elseif ($i == 2){
                $value['dj'] = '二级代理';
            }elseif ($i == 3){
                $value['dj'] = '三级代理';
            }
            $inArray[] = $value;
            $retArry[] = $value['id'];
        }
        $inArrays[] = $inArray;
        return $this->GetAgency($retArry,++$i);
    }

    /**
     * 创建一个无重复的推荐码
     * @return int
     */
    public function createCode()
    {
        $code = rand(100000,999999);
        $data = self::find()->where(['code'=>$code])->asArray()->select(['id'])->one();
        if($data)
            $code = $this->createCode();
        return $code;
    }
}