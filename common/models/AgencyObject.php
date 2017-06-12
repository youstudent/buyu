<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%agency}}".
 *
 * @property string $id
 * @property string $pid
 * @property string $phone
 * @property string $password
 * @property string $name
 * @property string $reg_time
 * @property string $gold
 * @property string $gold_all
 * @property string $identity
 * @property integer $status
 * @property string $code
 * @property integer $manage_id
 * @property string $manage_name
 * @property integer $rebate
 *
 * @property AgencyPayObject[] $agencyPays
 * @property RebateObject[] $rebates
 * @property UserPayObject[] $userPays
 */
class AgencyObject extends Object
{
    public static $goldConfig = [];
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'reg_time',  'gold_all', 'status', 'code'], 'integer'],
            [['phone'], 'string', 'max' => 12],
            [['password'], 'string', 'max' => 64],
            [['name', 'identity'], 'string', 'max' => 32],
            ['gold','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '代理ID',
            'phone' => '手机号',
            'password' => '操作密码',
            'name' => '用户名',
            'reg_time' => '注册时间',
            'gold' => '金币余额',
            'identity' => '身份证',
            'status' => '状态',
            'code' => '推荐码',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgencyPays()
    {
        return $this->hasMany(AgencyPayObject::className(), ['agency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRebates()
    {
        return $this->hasMany(RebateObject::className(), ['agency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPays()
    {
        return $this->hasMany(UserPayObject::className(), ['agency_id' => 'id']);
    }

    /**
     * 搜索处理数据函数
     * @return array
     */
    public function searchWhere()
    {
        if (!empty($this->select) && !empty($this->keyword))
        {
            if ($this->select == 'name')
                return ['name'=>$this->keyword];
            elseif ($this->select == 'phone')
                return ['phone'=>$this->keyword];
            elseif($this->select == 'identity')
                return ['identity'=>$this->keyword];
            else
                return ['or',['name'=>$this->keyword],['phone'=>$this->keyword],['identity'=>$this->keyword]];
        }
        return [];
    }


    /**
     * 获取用户的所有金币余额
     * @return array
     */
    public function getGold()
    {

        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        $data = [];
        foreach (self::$goldConfig as $key=>$value)
        {
            $data[$value['name']] = $this->getNoeGold($value['name']);
        }

        return $data;
    }

    /**
     * 获取一种货币的余额
     * @param string $name
     * @return mixed
     */
    public function getNoeGold($name = '')
    {
        $data = AgencyGoldObject::find()
                ->andWhere(['agency_id'=>$this->id])
                ->andWhere(['gold_config'=>$name])
                ->select(['gold'])
                ->asArray()
                ->one();
        return $data['gold'];
    }


    /**
     * 执行充值操作
     * @param $payGoldConfig
     * @param $payGold
     * @return bool
     */
    public function payGold($payGoldConfig,$payGold)
    {

        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        /**
         * 循环处理代码、避免数据库压力
         */
        foreach (self::$goldConfig as $key=>$value)
        {
            if($value['name'] == $payGoldConfig)
            {
                $data = AgencyGoldObject::find()
                        ->andWhere(['agency_id'=>$this->id])
                        ->andWhere(['gold_config'=>$payGoldConfig])
                        ->one();
                $data->gold       = ($data->gold + $payGold);
                $data->sum_gold   = ($data->sum_gold + $payGold);

                return $data->save();

            }
        }
    }

    /**
     * 执行充值操作
     * @param $payGoldConfig
     * @param $payGold
     * @return bool
     */
    public function consumeGold($payGoldConfig,$payGold)
    {

        if(empty(self::$goldConfig)){
            self::$goldConfig = GoldConfigObject::find()->asArray()->all();
        }

        /**
         * 循环处理代码、避免数据库压力
         */
        foreach (self::$goldConfig as $key=>$value)
        {
            if($value['name'] == $payGoldConfig)
            {
                $data = AgencyGoldObject::find()
                        ->andWhere(['agency_id'=>$this->id])
                        ->andWhere(['gold_config'=>$payGoldConfig])
                        ->one();

                $data->gold       = ($data->gold - $payGold);
                $data->sum_gold   = ($data->sum_gold - $payGold);

                return $data->save();
            }
        }
    }
}
