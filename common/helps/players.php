<?php

namespace common\helps;


use backend\models\Batteryrate;
use backend\models\PrewarningValue;
use backend\models\Redpacket;
use backend\models\Time;
use Codeception\Lib\Generator\Helper;
use common\models\Fishing;
use common\models\Player;
use common\models\VipUpdate;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\Types\Null_;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class players
{
    /**
     * @param $id
     * @return mixed
     * 获取玩家房间号
     */
    public static function getRoom($id)
    {
        $redis = self::getReids();
        if ($data = $redis->HGETALL('playerRoom')) {
            if (array_key_exists($id, $data)) {
                return $data[$id];
            }
            return 0;
        }
        return 0;
    }
    
    
    /**
     * @param $id
     * @return mixed
     * 根据当前玩家,该玩家房间号的其他人
     */
    public static function getRoomPlayer($id)
    {
        $Player_id=[];
        $redis = self::getReids();
        $playerRoom = null;
        $data = $redis->HGETALL('playerRoom');
        if ($data) {
            if (array_key_exists($id, $data)) {
                $playerRoom = $data[$id];
            }
            foreach ($data as $K=>$V){
                if ($V == $playerRoom && (int)$id!==$K){
                    $Player_id[]=$K;
                }
            }
        }
        //根据玩家查询 玩家信息
       $Player = Player::find()->select('name,id')->where(['id'=>$Player_id])->asArray()->all();
        //格式化数据
       $Player_new = ArrayHelper::map($Player,'id','name');
       return $Player_new;
        
    }
    
    /**
     * @param $id
     * @return string
     * 获取玩家命中率
     */
    public static function getPlayerRate($id)
    {
        $redis = self::getReids();
        // var_dump($redis->HGETALL('playerRate'));
        if ($data = $redis->HGETALL('playerRate')) {
            if (array_key_exists($id, $data)) {
                return $data[$id] / 100;
            }
            return 0;
        }
        return 0;
        
    }
    
    
    /**
     * @param $room
     * @return string
     * 查询房间命中率
     */
    public static function getRoomRate($room)
    {
        $redis = self::getReids();
       // var_dump($redis->HGETALL('roomRate'));
        if ($data = $redis->HGETALL('roomRate')) {
            if (array_key_exists($room, $data)) {
                return $data[$room]/100;
            }
            return 0;
        }
        return 0;
        
    }
    
    
    /**
     * @param $room
     * @return mixed
     * 获取玩家房间人数
     */
    public static function getRoomNmu($room)
    {
        $redis = self::getReids();
        if ($data = $redis->HGETALL('roomPlayerNum')) {
            if (array_key_exists($room, $data)) {
                return $data[$room];
            }
            return 0;
        }
        return 0;
    }
    
    /**
     * @return \Redis
     *  连接redis
     */
    public static function getReids()
    {
        $ip = \Yii::$app->params['redis'];
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);
        return $redis;
    }
    
    /**
     * @param $id
     * @param $type
     * @return int|mixed
     * 获取玩家预警值
     */
    public static function getwingvalue($id, $type)
    {
        if ($data = PrewarningValue::find()->select($type)->andWhere(['game_id' => $id])->asArray()->one()) {
            return $data[$type];
        }
        //如果没有给玩家加上预警值记录
        return 0;
        
    }
    
    /**
     * @param $id
     * @return float|int
     * 获取玩家vip等级的命中率
     */
    public static function getVipRate($id)
    {
        //查询玩家的vip等级
        $Player = Player::find()->select('viplevel')->where(['id' => $id])->asArray()->one();
        $viplevel = $Player['viplevel'];
        //查询vip等级的命中率
        if ($data = VipUpdate::findOne(['grade' => $viplevel])) {
            
            return $data->burst / 100;
        }
        return 0;
    }
    
    
    /**
     * @param $id
     * @return float|int
     * 获取 炮的命中率
     */
    public static function getBatteryRate($id)
    {
        //查询玩家使用炮的命中率
        $Player = Player::find()->select('battery')->where(['id' => $id])->asArray()->one();
        $battery = $Player['battery'];
        //查询玩家使用炮的命中率
        if ($data = Batteryrate::find()->select('rate')->where(['batteryid' => $battery])->asArray()->one()) {
            return $data['rate'] / 100;
        }
        return 0;
    }
    
    public static function getPlayerDetail(){
        $redis = self::getReids();
        $aa = $redis->hGetAll('goldGet');
        $aa= json_decode($aa[160], true);
    
        var_dump($aa);die;
        var_dump($redis->hGetAll('goldGet'));
        
        
    }
    
    /**
     * 查看权限
     */
    public static function actionPermission(){
        if (\Yii::$app->session->get('manageId')!== \Yii::$app->params['pa']->id && \Yii::$app->session->get('manageName')!==\Yii::$app->params['pa']->name)
        {
           throw new ForbiddenHttpException('没有该执行权限');
        }
    }
    /**
     *  根据登录用户显示按钮
     */
    public static function Permission(){
        if (\Yii::$app->session->get('manageId')== \Yii::$app->params['pa']->id && \Yii::$app->session->get('manageName')==\Yii::$app->params['pa']->name)
        {
            return true;
        }
    }
    
    
    /**
     *  根据登录用户显示按钮
     */
    public static function EditPermission(){
        if (\Yii::$app->session->get('manageId')== \Yii::$app->params['pa']->id && \Yii::$app->session->get('manageName')==\Yii::$app->params['pa']->name)
        {
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * 获取鱼群(添加红包鱼)
     * @return array
     */
    public static function getFishing($type){
        //根据类型查询所有的鱼
        $data = Fishing::find()->where(['type'=>$type])->asArray()->all();
        $data =  ArrayHelper::map($data,'id','name');
        $datas =  Redpacket::find()->asArray()->all();
        //查询所有已添加的鱼
        $re  = ArrayHelper::map($datas,'fishid','fishid');
        foreach ($re as $value){
            //循环删除
            if (array_key_exists($value,$data)){
                unset($data[$value]);
            }
        }
        return $data;
       
    }
    
    
    
    /**
     * 获取鱼群(添加红包鱼)
     * @return array
     */
    public static function getFishingss($type){
        //根据类型查询所有的鱼
        $data = Fishing::find()->where(['type'=>$type])->asArray()->all();
        $data =  ArrayHelper::map($data,'id','name');
        return $data;
        
    }
    
    /**
     * 根据 鱼的ID获取鱼的名字
     * @param $id
     * @return string
     */
    public static function getFishings($id){
      $data =   Fishing::findOne(['id'=>$id]);
      if ($data){
          return $data->name;
      }
    }
    
}