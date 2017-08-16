<?php

namespace backend\controllers;

use backend\models\Users;
use backend\models\UsersTime;
use common\helps\players;
use common\models\OnLine;
use common\models\Player;
use common\services\Request;
use spec\Prophecy\Exception\Prophecy\ObjectProphecyExceptionSpec;
use Symfony\Component\DomCrawler\Field\InputFormField;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ViewAction;

class MonitoringController extends ObjectController
{
    /** 监控中心首页
     * @return string
     */
    public function actionIndex()
    {
        //var_dump(\Yii::$app->redis->HGETALL('playerRoom'));exit;
        //$model = new Player();
        //$data = $model->GetOnLine();//获取在线的玩家
       /* $ip = "192.168.2.235";
        $port = 6379;
        $redis = new \Redis();
        $redis->pconnect($ip, $port, 1);*/
       // $redis = OnLine::getReids();
       // $D = $redis->HGETALL('playerRoom');
        //查询在线玩家   HGETALL roomPlayerNumR人家人数
        
        //HGETALL playerRoom 玩家在那个房间
        //HGETALL roomPlayerNum 房间有多少人
        //SMEMBERS onlinePlayer 在线玩家
    
        //$D = $redis->HGETALL('playerRoom');
       // $D = \Yii::$app->redis->HGETALL('roomPlayerNum');
        //var_dump($D[251]);exit;
       // $D = $redis->HGETALL('roomPlayerNum');
       // print_r($D["\"251\""]);EXIT;
        //$Player  = $redis->SMEMBERS ('onlinePlayer');
        $redis = players::getReids();
        $Player =$redis->SMEMBERS('onlinePlayer');
        $model = Player::find();
        $model->andWhere(['id'=>$Player]);
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => 1
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->render('index', ['data' => $data,'pages'=>$pages]);
    }
    
    
    
    public function actionNew(){
       // $ip = "192.168.2.235";
       // $port = 6379;
       // $redis = new \Redis();
       // $redis->pconnect($ip, $port, 1);
        //查询在线玩家
        //$D = $redis->HGETALL('playerRoom');
        
        // print_r($D["\"251\""]);EXIT;
       // $Player  = $redis->SMEMBERS ('onlinePlayer');
        $redis = players::getReids();
        $Player =$redis->SMEMBERS('onlinePlayer');
        $model = Player::find();
        $model->andWhere(['id'=>$Player]);
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => 1
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->renderAjax('new-index', ['data' => $data,'pages'=>$pages]);
    }
    
    
    /**
     *  监控数据
     */
    public function actionGet()
    {
        header("Content-Type: text/json;charset=utf-8");
        $re1 = rand(1, 2000);
        $re2 = rand(1, 2000);
        $re3 = rand(1, 2000);
        $re4 = rand(1, 2000);
        $re5 = rand(1, 2000);
        
        $zuanshi1 = rand(1, 2000);
        $zuanshi2 = rand(1, 2000);
        $zuanshi3 = rand(1, 2000);
        $zuanshi4 = rand(1, 2000);
        $zuanshi5 = rand(1, 2000);
        $students = [
            ['id' => 1, 'room' => '1', 'num' => '1', 'bei' => '12', "name" => "龙龙", 'name_id' => '3211', 'gold' => $re1, 'zuanshi' => $zuanshi1],
            ['id' => 2, 'room' => '4', 'num' => '3', 'bei' => '10', "name" => "勇勇", 'name_id' => '3212', 'gold' => $re2, 'zuanshi' => $zuanshi2],
            ['id' => 3, 'room' => '12', 'num' => '4', 'bei' => '30', "name" => "强强", 'name_id' => '344211', 'gold' => $re3, 'zuanshi' => $zuanshi3],
            ['id' => 4, 'room' => '15', 'num' => '2', 'bei' => '12', "name" => "张三", 'name_id' => '13112', 'gold' => $re4, 'zuanshi' => $zuanshi4],
            ['id' => 5, 'room' => '28', 'num' => '1', 'bei' => '21', "name" => "李四", 'name_id' => '421211', 'gold' => $re5, 'zuanshi' => $zuanshi5],
        ];
        
        echo json_encode($students);
        
    }
    
    
    /**
     *  机器人指派
     */
    public function actionRobot($room_id, $game_id)
    {
        $data = OnLine::find()->where(['room_id' => $room_id])->all();
        $datas = [];
        foreach ($data as $k => $v) {
            $datas[$v->users->game_id] = $v->users->nickname;
        }
        return $this->render('robot');
        
    }
    
    
    /**
     *  监控中心掉线接口
     */
    public function actionLostConnection()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $datas['playerId'] = $id;
        //掉线的接口
        $result = Request::request_post(\Yii::$app->params['Api'] . '/gameserver/control/ban', $datas);
        if ($result['code'] == 1) {
            return ['code' => 1, 'message' => \Yii::t('app', '操作成功')];
        }
        
    }
    
    
    /**
     * 监控中心停封玩家
     */
    public function actionStop()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $datas['playerId'] = $id;
        $datas['banTime'] = 86400 * 1000;//将时间戳转化成毫秒
        $result = Request::request_post(\Yii::$app->params['Api'] . '/gameserver/control/ban', $datas);
        if ($result['code'] == 1) {
            $user_time = new UsersTime();
            $user_time->game_id = $id;
            $user_time->time = strtotime("+1 day");
            $user_time->save(false);
            return ['code' => 1, 'message' => \Yii::t('app', '操作成功')];
        }
    }
    
    /**
     * 命中率详情设置
     */
    public function actionRate()
    {
        
        return $this->render('rate');
    }
    
    
    /**
     * 预警值设置(金币,宝石)
     */
    public function actionWarning()
    {
    
    
    
    }
    
    
    /**
     *  玩家的钻石和宝石的消费详情
     */
    public function actionGameDetail()
    {
    
    
    }
    
    
    public function actionPage(){
        $rawData = array(
            array('id'=>1,'username'=>'aa','password'=>'aaaaaa'),
            array('id'=>2,'username'=>'bb','password'=>'aaaaaa'),
            array('id'=>3,'username'=>'cc','password'=>'aaaaaa'),
            array('id'=>4,'username'=>'dd','password'=>'aaaaaa'),
            array('id'=>5,'username'=>'ee','password'=>'aaaaaa'),
            array('id'=>6,'username'=>'ff','password'=>'aaaaaa'),
            array('id'=>7,'username'=>'gg','password'=>'aaaaaa'),
            array('id'=>8,'username'=>'hh','password'=>'aaaaaa'),
            array('id'=>9,'username'=>'jj','password'=>'aaaaaa'),
            array('id'=>10,'username'=>'qq','password'=>'aaaaaa'),
            array('id'=>11,'username'=>'www','password'=>'aaaaaa'),
            array('id'=>12,'username'=>'xx','password'=>'aaaaaa'),
            array('id'=>13,'username'=>'zz','password'=>'aaaaaa'),
        );
    
    
        $provider = new ArrayDataProvider([
            'query' => $rawData,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'username' => SORT_ASC,
                ]
            ],
        ]);
        
       // var_dump($provider->pagination);exit;
        /*$dataProvider = new ActiveDataProvider($rawData, array(
            'sort'=>array(
                'attributes'=>array(
                    'id', 'username', 'password',
                ),
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));*/
      return $this->render('page',array('dataProvider'=>$provider));
    }
    
}
