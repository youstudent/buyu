<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */


namespace backend\models;

use common\models\AgencyGoldObject;
use common\models\AgencyObject;
use common\models\GoldConfigObject;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Class Agency
 * @package backend\models
 */
class Agency extends AgencyObject
{
    /**
     * 充值类型
     * @var string
     */
    public $pay_gold_config = '';

    /**
     * 所有金币数量
     * @var array
     */
    public $goldArr = [];

    /**
     * 账号状态筛选功能
     * @var int
     */
    public $searchstatus = '' ;

    /**
     * 用户推荐码
     * @var string
     */
    public $recode       = '';

    /**
     * 充值金额
     * @var int
     */
    public $pay_gold      = 0;

    /**
     * 扣除金币
     * @var int
     */
    public $deduct_gold   = 0;

    /**
     * 体现备注
     * @var string
     */
    public $deduct_notes       = 0;

    /**
     * 体现备注
     * @var string
     */
    public $deduct_money       = '';

    /**
     * 充值时收的人民币
     * @var int
     */
    public $pay_money    = 0;

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
     * 上级代理名称
     * @var string
     */
    public $pName        = '';

    public function rules()
    {
        return [
            [['pid', 'reg_time',  'status', 'code','recode','pay_money'], 'integer'],
            [['gold_all','pay_gold','deduct_money'],'number'],
            [['phone'], 'string', 'max' => 12],
            [['password'], 'string', 'max' => 64],
            [['name', 'identity'], 'string', 'max' => 32],
            [['searchstatus','keyword','gold','select','deduct_notes','pay_gold_config'],'safe'],
            [['phone','password','name','identity'],'required'],
            [['phone'],'match','pattern'=>'/^((13[0-9])|(15[^4])|(18[0,2,3,5-9])|(17[0-8])|(147))\\d{8}$/'],
//            [['identity'],'match','pattern'=>'/(^\d{15}$)|(^\d{6}[1|2]\d{10}(\d|X|x)$)/'],
            [['identity'],'match','pattern'=>'/(^\d{15}$)|\d{6}(18|19|20)\d{2}(0[1-9]|1[1-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])\d{3}(\d{1}|X)$/'],
            ['recode','validateCodeExist'],
            ['phone','validatePhoneExist','on'=>'add'],
            ['pay_gold','match','pattern'=>'/^\+?[1-9][0-9]*$/','on'=>'pay'],
            ['deduct_gold','match','pattern'=>'/^(([1-9][0-9]*)|(([0]\.\d{1,2}|[1-9][0-9]*\.\d{1,2})))$/','on'=>'deduct'],
            ['deduct_gold','validateDeduct','on'=>'deduct'],
            [['starttime','endtime','detail'],'safe'],
        ];
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
            $data = self::find()->where(['code'=>$this->recode])->select(['id'])->asArray()->one();
            if($data)
                return $this->pid = $data['id'];
            return $this->addError($attribute,\Yii::t('app','recode_no_exist'));
        }
    }

    /**
     * 严重扣除金币是否符合要求
     * @param $attribute
     * @param $params
     */
    public function validateDeduct($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $gole = $this->getNoeGold($this->pay_gold_config);
            if($gole < $this->deduct_gold){
                $this->addError($attribute,'扣除金额不能大于现有余额！');
            }
        }
    }
    /**
     * 判断手机号码是否存在
     * @param $attribute
     * @param $params
     */
    public function validatePhoneExist($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(self::find()->where(['phone'=>$this->phone])->select(['id'])->asArray()->one())
                return $this->addError($attribute,\Yii::t('app','phone_no_exist'));
        }
    }

    public function attributeLabels()
    {
        $arr=[
            'recode' => '推荐码',
            'pay_gold'=>'充值数量',
            'pay_money'=>'收款金额',
            'deduct_gold' =>'扣除数量',
            'deduct_notes'=>'扣除备注',
            'deduct_money'  =>'人民币',
            'pay_gold_config'=>'充值类型',
            'detail'=>'备注',
            ];
        return ArrayHelper::merge(parent::attributeLabels(),$arr);
    }


    /**
     * 返回上级代理
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function structureChart($id)
    {
        $data = self::findOne($id);
        return self::find()->where(['id'=>$data['id']])->one();
    }

    /**
     * 代理商扣除操作
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function deduct($data)
    {
        $this->scenario = 'deduct';
        if($this->load($data) && $this->validate())
        {   $transaction = \Yii::$app->db->beginTransaction();
            try{
                $model = self::findOne($this->id);
                $data = $model->consumeGold($this->pay_gold_config,$this->deduct_gold);

                if($data == false)throw  new \Exception('0x00010');
                $agencyPay = new AgencyPay();
                $agencyPay->agency_id = $model->id;
                $agencyPay->name      = $model->name;
                $agencyPay->time      = time();
                $agencyPay->gold      = $this->deduct_gold;
                $agencyPay->money     = $this->pay_money;
                //$agencyPay->notes     = $this->deduct_notes;  //充值备注
                $agencyPay->type    = '扣除';
                $agencyPay->status    = 2;
                $agencyPay->manage_id = \Yii::$app->session->get('manageId');
                $agencyPay->manage_name = \Yii::$app->session->get('manageName');
                $agencyPay->gold_config = $this->pay_gold_config;
                /*$agencyDeduct = new AgencyDeduct();
                $agencyDeduct->agency_id    = $model->id;
                $agencyDeduct->name         = $model->name;
                $agencyDeduct->time         = time();
                $agencyDeduct->gold         = $this->deduct_gold;
                $agencyDeduct->money        = $this->deduct_money;
                $agencyDeduct->notes        = $this->deduct_notes;
                $agencyDeduct->status       = 2;
                $agencyDeduct->type         = 1; //代理扣除
                $agencyDeduct->manage_id    = \Yii::$app->session->get('manageId');
                $agencyDeduct->manage_name  = \Yii::$app->session->get('manageName');
                $agencyDeduct->gold_config  = $this->pay_gold_config;*/
                if($agencyPay->save(false) == false)throw new \Exception('0x00011');

                $transaction->commit();
                return true;
            }catch (\Exception $exception){
                $transaction->rollBack();
                throw $exception;
            }
        }
    }

    /**
     * 代理商充值
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function pay($data)
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            $transaction  = \Yii::$app->db->beginTransaction();
            try{
                $model = self::findOne($this->id);
                $data  = $model->payGold($this->pay_gold_config,$this->pay_gold);

                if(!$data) throw  new \Exception("model保存数据失败");

                $agencyPay = new AgencyPay();
                $agencyPay->agency_id = $model->id;
                $agencyPay->name      = $model->name;
                $agencyPay->time      = time();
                $agencyPay->gold      = $this->pay_gold;
                $agencyPay->money     = $this->pay_money;
                $agencyPay->notes     = $this->detail;  //充值备注
                $agencyPay->status    = 2;
                $agencyPay->type    = '充值';
                $agencyPay->manage_id = \Yii::$app->session->get('manageId');
                $agencyPay->manage_name = \Yii::$app->session->get('manageName');
                $agencyPay->gold_config = $this->pay_gold_config;
                if($agencyPay->save()== false) throw new \Exception("agencyPay保存数据失败");
                if(\Yii::$app->params['distribution'])
                {
                    $rebateConf = RebateConf::findOne(1);

                    ##由于时间问题、此处算法不佳
                    #一级
                    if ($model->pid != 0) {
                        $one = self::findOne($model->pid);
                        $rebate = new Rebate();
                        $rebate->agency_pay_user_id = $model->id;
                        $rebate->agency_pay_id = $agencyPay->id;
                        $rebate->agency_id     = $one->id;
                        $rebate->gold_num      = ($agencyPay->gold*($rebateConf->one/100));
                        $rebate->notes         = '';
                        $rebate->time          = time();
                        $rebate->agency_pay_name = $model->name;
                        $rebate->agency_name     = $one->name;
                        $rebate->proportion      = $rebateConf->one;
                        $rebate->notes           = "代理:".$model->name." 充值".$this->pay_gold_config.$agencyPay->gold." ，你是一级代理，按返佣比例".$rebateConf->one."%，你返利".$rebate->gold_num."个钻石";
                        $rebate->rebate_conf   = "一级代理";
                        if($rebate->save() == false)throw new \Exception("一级代理保存数据失败");

                        $data = $one->payGold($this->pay_gold_config,$rebate->gold_num);
                        if($data== false) throw  new \Exception("0x00001");

                        #二级
                        //var_dump($agencyPay->id);exit();
                        if($one->pid != 0){
                            $two = self::findOne($one->pid);
                            $rebate = new Rebate();
                            $rebate->agency_pay_user_id = $model->id;
                            $rebate->agency_pay_id   = $agencyPay->id;
                            $rebate->agency_id       = $two->id;
                            $rebate->gold_num        = ($agencyPay->gold*($rebateConf->two/100));
                            $rebate->notes           = '';
                            $rebate->time            = time();
                            $rebate->agency_pay_name = $model->name;
                            $rebate->agency_name     = $two->name;
                            $rebate->proportion      = $rebateConf->two;
                            $rebate->notes           = "代理:".$model->name." 充值".$this->pay_gold_config.$agencyPay->gold." ，你是二级代理，按返佣比例".$rebateConf->two."%，你返利".$rebate->gold_num."个钻石";
                            $rebate->rebate_conf     = "二级代理";
                            if($rebate->save() == false)throw new \Exception("二级代理保存数据失败");

                            $data = $two->payGold($this->pay_gold_config,$rebate->gold_num);
                            if($data == false) throw  new \Exception("0x00002");

                            #三级
                            if($two->pid != 0){
                                $three = self::findOne($two->pid);
                                $rebate = new Rebate();
                                $rebate->agency_pay_user_id = $model->id;
                                $rebate->agency_pay_id = $agencyPay->id;
                                $rebate->agency_id     = $three->id;
                                $rebate->gold_num      = ($agencyPay->gold*($rebateConf->three/100));
                                $rebate->notes         = '';
                                $rebate->time          = time();
                                $rebate->agency_pay_name = $model->name;
                                $rebate->agency_name     = $three->name;
                                $rebate->proportion      = $rebateConf->three;
                                $rebate->notes           = "代理:".$model->name." 充值".$this->pay_gold_config.$agencyPay->gold." ，你是三级代理，按返佣比例".$rebateConf->three."%，你返利".$rebate->gold_num."个钻石";
                                $rebate->rebate_conf   = "三级代理";
                                if($rebate->save() == false)throw new \Exception("三级代理保存数据失败");

                                $data = $three->payGold($this->pay_gold_config,$rebate->gold_num);
                                if($data == false) throw  new \Exception("0x00003");
                            }
                        }
                    }
                }

                $transaction->commit();
                return true;

            }catch (\Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
    }
    /**
     * 搜索功能
     * @param $data
     * @return array
     */
    public function search($data)
    {
        $this->load($data);
        $this->initTime();
        $model = self::find()->where($this->searchWhereLike())
            ->andWhere(['like','status',$this->searchstatus])
            ->andWhere(['>=','reg_time',strtotime($this->starttime)])
            ->andWhere(['<=','reg_time',strtotime($this->endtime)]);;

        $pages = new Pagination(['totalCount' =>$model->count(), 'pageSize' => \Yii::$app->params['pageSize']]);
        $data = $model->limit($pages->limit)->offset($pages->offset)->all();

        foreach ($data as $key=>$val)
        {

            $datas =  self::findOne($val['pid']);
            if($datas){
                $data[$key]['pName'] = $datas->name;
            }else{
                $data[$key]['pName'] = '平台';
            }
            $data[$key]['gold']  = $val->getGold();
        }
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    /**
     * 平台添加代理商
     * @param array $data
     * @return bool
     */
    public function add($data = [])
    {
        $this->scenario = 'add';
        if($this->load($data) && $this->validate())
        {
            $this->reg_time    = time();
            $this->gold        = 0;
            $this->gold_all    = 0;
            $this->status      = 1;
            $this->code        =$this->createCode();
            $this->manage_id   = \Yii::$app->session->get('manageId');
            $this->manage_name = \Yii::$app->session->get('manageName');

            if($this->save())
            {
                $data = GoldConfigObject::find()->asArray()->all();

                foreach ($data as $key=>$value)
                {
                    $agencyGold = new AgencyGoldObject();
                    $agencyGold->agency_id   = $this->id;
                    $agencyGold->gold_config = $value['name'];
                    $agencyGold->gold        = 0;
                    $agencyGold->sum_gold    = 0;
                    if($agencyGold->save() == false){
                        $message = $agencyGold->getFirstErrors();
                        $message = reset($message);
                        $this->addError('id',$message);
                        return false;
                    }
                }

                return true;
            }
        }
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
                return $code = $this->createCode();
            return $code;
    }

    /**
     * 搜索处理数据函数
     * @return array
     */
    public function searchWhereLike()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'name')
                return ['like','name',$this->keyword];
            elseif ($this->select == 'phone')
                return ['like','phone',$this->keyword];
            elseif($this->select == 'identity')
                return ['like','identity',$this->keyword];
            else
                return ['or',['name'=>$this->keyword],['like','phone',$this->keyword],['like','identity',$this->keyword]];
        }
        return [];
    }


    /**
     * 处理数组 [1,2,3]
     * @param $data
     * @return array
     */
    private function searchIn($data)
    {
        $in = [];
        foreach ($data as $item)
            $in[] = $item['id'];
        return $in;
    }

    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if($this->starttime == '') {
            $this->starttime = \Yii::$app->params['startTime'];
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
}