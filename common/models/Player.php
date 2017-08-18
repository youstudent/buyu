<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "player".
 *
 * @property integer $id
 * @property string $name
 * @property string $head
 * @property string $openid
 * @property integer $sex
 * @property string $gold
 * @property string $diamond
 * @property string $fishGold
 * @property string $mac
 * @property string $uid
 * @property string $pwd
 * @property string $ex
 * @property integer $deposit
 * @property integer $battery
 * @property integer $power
 * @property integer $maxpower
 * @property integer $level
 * @property string $phonenumber
 * @property string $idcard
 * @property string $realname
 * @property integer $viplevel
 * @property string $province
 * @property string $onlinetime
 * @property string $createdtime
 * @property string $lastlogintime
 * @property integer $trident
 * @property integer $fishtrident
 * @property integer $familyowner
 *
 * @property Messageboard[] $messageboards
 */
class Player extends \yii\db\ActiveRecord
{
    
    public  $unset_time;  //保存时间
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->commondb;
        //return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gold', 'diamond', 'fishGold', 'ex', 'deposit', 'battery', 'power', 'maxpower', 'level', 'viplevel', 'onlinetime', 'lastlogintime', 'trident', 'fishtrident', 'familyowner'], 'integer'],
            [['onlinetime'], 'required'],
            [['createdtime','unset_time','sex','mac','head'], 'safe'],
            [['name', 'openid', 'uid', 'pwd', 'phonenumber', 'idcard', 'realname', 'province'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '玩家昵称',
            'head' => 'Head',
            'openid' => 'Openid',
            'sex' => 'Sex',
            'gold' => '金币',
            'diamond' => 'Diamond',
            'fishGold' => 'Fish Gold',
            'mac' => 'Mac',
            'uid' => 'Uid',
            'pwd' => '密码',
            'ex' => 'Ex',
            'deposit' => 'Deposit',
            'battery' => 'Battery',
            'power' => 'Power',
            'maxpower' => 'Maxpower',
            'level' => 'Level',
            'phonenumber' => 'Phonenumber',
            'idcard' => 'Idcard',
            'realname' => 'Realname',
            'viplevel' => 'Viplevel',
            'province' => 'Province',
            'onlinetime' => 'Onlinetime',
            'createdtime' => 'Createdtime',
            'lastlogintime' => 'Lastlogintime',
            'trident' => 'Trident',
            'fishtrident' => 'Fishtrident',
            'familyowner' => 'Familyowner',
            'unset_time' => '解封时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getMessageboards()
    {
        return $this->hasMany(Messageboard::className(), ['playerid' => 'id']);
    }*/
    
    
    /**
     *  查询在线的玩家
     */
    public function GetOnLine(){
        $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        //查询在线玩家
        $Player  = $redis->SMEMBERS ('onlinePlayer');
        $model = self::find();
        $model->andWhere(['id'=>22]);
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->all();
        return $data;
    }
}
