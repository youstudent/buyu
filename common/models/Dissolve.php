<?php

namespace common\models;

use backend\models\Agency;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%dissolve}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $family_id
 * @property string $re_name
 * @property string $manage_name
 * @property integer $manage_id
 * @property integer $time
 * @property integer $status
 */
class Dissolve extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dissolve}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['family_id', 'manage_id', 'time', 'status'], 'integer'],
            [['name', 're_name', 'manage_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'family_id' => 'Family ID',
            're_name' => 'Re Name',
            'manage_name' => 'Manage Name',
            'manage_id' => 'Manage ID',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }
    
    
    /**
     *  解散家族
     */
    public function pass($id){
        $transaction  = \Yii::$app->db->beginTransaction();
        try{
            $dissolve = Dissolve::findOne($id);
            if ($dissolve== false || $dissolve==null){
                throw new \Exception("解散记录查询失败");
            }
            if ($dissolve->status ==1 || $dissolve->status ==2){
                throw new \Exception("数据已经处理过了");
            }
            $dissolve->manage_id=\Yii::$app->session->get('manageId');
            $dissolve->manage_name=\Yii::$app->session->get('manageName');
            $dissolve->time=time();
            $dissolve->status=1;
            if ($dissolve->save()==false) throw new Exception('状态改变失败');
            //家族宝箱箱的钱退给玩家
            $family =Familyplayer::find()->where(['familyid'=>$dissolve->family_id,'status'=>1])->asArray()->all();
            foreach ($family as $value){
               //查找玩家退换保险箱钱
                $Player = Player::findOne($value['playerid']);
                if ($Player){
                    $Player->gold=$Player->gold+$value['gold'];
                    $Player->diamond=$Player->diamond+$value['diamond'];
                    $Player->fishGold=$Player->fishGold+$value['fishgold'];
                   if ($Player->save()==false) throw new Exception('退换玩家货币失败');
                }
            }
            //删除族员与族长管理信息
            if (Familyplayer::deleteAll(['familyid'=>$dissolve->family_id])==false) throw new Exception('族长和族员关系删除失败');
            //删除家族动态
            if (Familyrecord::deleteAll(['familyid'=>$dissolve->family_id])==false) throw  new Exception('删除我的动态失败');
            //找出族长列表
            $Family = Family::findOne(['id'=>$dissolve->family_id]);
            if ($Family ==null) throw  new Exception('未找到族长');
            //删除玩家族长关联的玩家
            $Players = Player::findOne(['id'=>$Family->ownerid]);
            if ($Players == null) throw new Exception('族长管理玩家未找到');
            //删除玩家
            if ($Players->delete()==false) throw new Exception('族长关联玩家删除失败');
            //删除族长
            if ($Family->delete() ==false) throw new Exception('删除族长失败');
            //处理代理登录账号
            if (Agency::deleteAll(['family'=>$dissolve->family_id])==false) throw new Exception('删除后台代理账号失败');
            $transaction->commit();//提交事务
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();//回滚事务
            throw $e;
        }
    }
}
