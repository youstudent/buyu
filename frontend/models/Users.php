<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace frontend\models;

use common\models\GoldConfigObject;
use common\models\UsersObject;
use common\services\Request;
use yii\helpers\ArrayHelper;

class Users extends UsersObject{

    /**
     * 用户充值的金币数量
     * @var string
     */
    public $pay_gold_num = 0;

    /**
     * 充值时候的金额
     * @var int
     */
    public $pay_money    = 0;

    /**
     * @var array
     */
    public $goldArr    = [];
    /**
     * 充值类型
     * @var string
     */
    public $pay_gold_config = 'gold';

    public function rules()
    {
        return [
            ['pay_gold_num','integer','on'=>'pay'],
            ['pay_gold_num','match','pattern'=>'/^\+?[1-9][0-9]*$/','on'=>'pay'],
            ['pay_money','number','on'=>'pay'],
            ['pay_gold_config','safe']
        ];
    }


    public function attributeLabels()
    {
        $arr = [
            'pay_gold_num'    =>'充值金额',
            'pay_money'       =>'收款',
            'pay_gold_config' =>'充值类型'
        ];
        return ArrayHelper::merge(parent::attributeLabels(),$arr);
    }

    /**
     * 用户充值功能
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function pay($data = [])
    {
        $this->scenario = 'pay';
        if($this->load($data) && $this->validate())
        {
            /**
             * 查询用户是否存在
             */
            $model = self::findOne($data['id']);
            if($model)
            {
                $agencyModel = Agency::findOne(\Yii::$app->session->get('agencyId'));
                $gold = $agencyModel->getNoeGold('房卡');
                if ($gold < $this->pay_gold_num)
                {
                    return $this->addError('pay_gold_num','对不起你的数量不足！');
                }

                /**
                 * 请求游戏服务器、并判断返回值进行逻辑处理
                 */
                $url = \Yii::$app->params['ApiUserPay']."?mod=gm&act=chargeCard&uid=".$model->game_id."&card=".$this->pay_gold_num;
                $data = Request::request_get($url);
                if($data['code'] == 1)
                {
                    /**
                     * 开启数据库的事务操作
                     */
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        /**
                         * 减少金币数量
                         */
                        $data = $agencyModel->consumeGold($this->pay_gold_config,$this->pay_gold_num);
                        if(!$data)
                            throw new \Exception('save error 101028'); /* 保存失败抛出异常 */
                        /**
                         * 保存到数据表
                         */
                        $data = $model->payGold($this->pay_gold_config,$this->pay_gold_num);
                        if (!$data)
                            throw new \Exception('save error 101023'); /* 保存失败抛出异常 */

                        /**
                         * 保存用户充值记录
                         */
                        $userModel = new UserPay();
                        $userModel->agency_id   = $agencyModel->id;
                        $userModel->agency_name = $agencyModel->name;
                        $userModel->user_id     = $model->id;
                        $userModel->game_id     = $model->game_id;
                        $userModel->nickname    = $model->nickname;
                        $userModel->time        = time();
                        $userModel->gold        = $this->pay_gold_num;
                        $userModel->money       = $this->pay_money;
                        $userModel->status      = 1;
                        $userModel->gold_config = $this->pay_gold_config;


                        /* 保存失败抛出异常 */
                        if ($userModel->save()) {
                            $transaction->commit();
                            return true;
                        }else
                            throw new \Exception('save error 101024');

                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }else{
                    return $this->addError('pay',$data['message']);
                }
            }
        }
    }
}