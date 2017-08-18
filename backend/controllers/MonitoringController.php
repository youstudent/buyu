<?php

namespace backend\controllers;

use backend\models\PrewarningValue;
use backend\models\RateForm;
use backend\models\RobotForm;
use backend\models\Roomrate;
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
use yii\helpers\Json;
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
        return $this->render('index');
    }
    
    /**
     * @return string
     * 用户数据
     */
    public function actionNew()
    {
        $redis = players::getReids();
        $Player = $redis->SMEMBERS('onlinePlayer');//获取在线的人数
        $datas=[2,60,254];
        $filed = 'GREATEST(`gold`,`diamond`,`fishGold`) as t,id,name,gold,diamond,fishGold';
        $model = Player::find()->select($filed)->orderBy(['t'=> SORT_DESC]);
        $model->andWhere(['id' => $datas]);
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'defaultPageSize' => 1,
                'route' => 'monitoring/index',
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->renderAjax('new-index', ['data' => $data, 'pages' => $pages]);
    }
    
    
    /**
     * @return string
     * 用户分页
     */
    public function actionPages()
    {
        $redis = players::getReids();
        $Player = $redis->SMEMBERS('onlinePlayer');//获取在线的人数
        $datas=[2,60,254];
        $filed = 'GREATEST(`gold`,`diamond`,`fishGold`) as t,id,name,gold,diamond,fishGold';
        $model = Player::find()->select($filed)->orderBy(['t'=> SORT_DESC]);
        $model->andWhere(['id' => $datas]);
        
        $pages = new Pagination(
            [
                'totalCount' => $model->count(),
                'pageSize' => 5
            ]
        );
        $data = $model->limit($pages->limit)->offset($pages->offset)->asArray()->all();
        return $this->renderAjax('pages', ['data' => $data, 'pages' => $pages]);
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
    public function actionRobot()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = new RobotForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->editRate(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        }
        //查询玩家在那个房间
        //$room = players::getRoomPlayer($id);
        //查询晚间房间的人数
        
        $model->id = $id;
        return $this->render('robot', ['model' => $model]);
        
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
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = new RateForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->editRate(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        }
        $model->vip_rate = players::getVipRate($id);
        $model->battery_rate = players::getBatteryRate($id);
        $model->player_rate = players::getPlayerRate($id);
        $model->room_rate = players::getRoomRate(players::getRoom($id));
        $model->id = $id;
        return $this->render('rate', ['model' => $model]);
    }
    
    
    /**
     * 预警值设置(金币,宝石)
     */
    public function actionWarning()
    {
        
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = PrewarningValue::findOne(['game_id' => $id]);
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->editRate(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        }
        return $this->render('warning', ['model' => $model]);
        
    }
    
    public function actionPage()
    {
        $rawData = array(
            array('id' => 1, 'username' => 'aa', 'password' => 'aaaaaa'),
            array('id' => 2, 'username' => 'bb', 'password' => 'aaaaaa'),
            array('id' => 3, 'username' => 'cc', 'password' => 'aaaaaa'),
            array('id' => 4, 'username' => 'dd', 'password' => 'aaaaaa'),
            array('id' => 5, 'username' => 'ee', 'password' => 'aaaaaa'),
            array('id' => 6, 'username' => 'ff', 'password' => 'aaaaaa'),
            array('id' => 7, 'username' => 'gg', 'password' => 'aaaaaa'),
            array('id' => 8, 'username' => 'hh', 'password' => 'aaaaaa'),
            array('id' => 9, 'username' => 'jj', 'password' => 'aaaaaa'),
            array('id' => 10, 'username' => 'qq', 'password' => 'aaaaaa'),
            array('id' => 11, 'username' => 'www', 'password' => 'aaaaaa'),
            array('id' => 12, 'username' => 'xx', 'password' => 'aaaaaa'),
            array('id' => 13, 'username' => 'zz', 'password' => 'aaaaaa'),
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
        return $this->render('page', array('dataProvider' => $provider));
    }
    
    
    /**
     *  查询玩家获取的金币
     */
    public function actionGetGold()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $redis = players::getReids();
        //$id = 160;
        
        /**
         *  获取金币的途径
         */
        $new_gold = null;
        $gold = $redis->hGetAll('goldGet');
        if (array_key_exists($id, $gold)) {
            $new_gold = Json::decode($gold[$id], true);
        }
        $data = [];
        if ($new_gold){
            foreach ($new_gold as $key => $value) {
                if (array_key_exists('duihuan', $new_gold)) {
                    $data['兑换'] = $value;
                }
                if (array_key_exists('buyu', $new_gold)) {
                    $data['捕鱼'] = $value;
                }
                if (array_key_exists('buyusongbi', $new_gold)) {
                    $data['捕鱼送币'] = $value;
                }
                if (array_key_exists('chongzhi', $new_gold)) {
                    $data['充值'] = $value;
                }
                if (array_key_exists('cijiyouxi', $new_gold)) {
                    $data['刺激游戏'] = $value;
                }
                if (array_key_exists('duihuanma', $new_gold)) {
                    $data['兑换码'] = $value;
                }
                if (array_key_exists('gonggao', $new_gold)) {
                    $data['公告'] = $value;
                }
                if (array_key_exists('jiujijin', $new_gold)) {
                    $data['救济金'] = $value;
                }
                if (array_key_exists('meiriqiandao', $new_gold)) {
                    $data['每日签到'] = $value;
                }
                if (array_key_exists('meirirenwu', $new_gold)) {
                    $data['每日任务'] = $value;
                }
                if (array_key_exists('vipmeiri', $new_gold)) {
                    $data['vip任务'] = $value;
                }
                if (array_key_exists('youjian', $new_gold)) {
                    $data['邮件'] = $value;
                }
        
        
            }
       
        }
        
        /**
         *  获取玩家,消费途径
         */
        $new_gold_lost = null;
        $gold_lost = $redis->hGetAll('goldLost');
        if (array_key_exists($id, $gold_lost)) {
            $new_gold_lost = Json::decode($gold_lost[$id], true);
        }
        $datas = [];
        if ($new_gold_lost){
            foreach ($new_gold_lost as $key => $value) {
                if (array_key_exists('buyuxiaohao', $new_gold_lost)) {
                    $datas['消耗'] = $value;
                }
                if (array_key_exists('cijiyouxi', $new_gold_lost)) {
                    $datas['刺激游戏'] = $value;
                }
        
            }
            
        }
        
        return $this->render('gold', ['data' => $data, 'datas' => $datas]);
    }
    
    
    /**
     *  查询玩家获取钻石
     */
    public function actionGetDiamond()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $redis = players::getReids();
       // $id = 254;
        
        /**
         *  获取金币的途径
         */
        $new_gold = null;
        $gold = $redis->hGetAll('diamondGet');
        if (array_key_exists($id, $gold)) {
            $new_gold = Json::decode($gold[$id], true);
        }
        //  254 => string '{"@class":"com.sinysoft.gameserver.common.DiamondGet","buyu":0,"buyusongbi":0,"chongzhi":4011,"duihuanma":0,
        //"gonggao":0,"meiriqiandao":0,"meirirenwu":0,"youjian":0}' (length=164)
        $data = [];
        if ($new_gold){
            foreach ($new_gold as $key => $value) {
                if (array_key_exists('buyu', $new_gold)) {
                    $data['捕鱼'] = $value;
                }
                if (array_key_exists('buyusongbi', $new_gold)) {
                    $data['捕鱼送币'] = $value;
                }
                if (array_key_exists('chongzhi', $new_gold)) {
                    $data['充值'] = $value;
                }
                if (array_key_exists('duihuanma', $new_gold)) {
                    $data['兑换码'] = $value;
                }
                if (array_key_exists('gonggao', $new_gold)) {
                    $data['公告'] = $value;
                }
                if (array_key_exists('meiriqiandao', $new_gold)) {
                    $data['每日签到'] = $value;
                }
                if (array_key_exists('meirirenwu', $new_gold)) {
                    $data['每日任务'] = $value;
                }
                if (array_key_exists('youjian', $new_gold)) {
                    $data['邮件'] = $value;
                }
        }
       
            
        }
        //"@class":"com.sinysoft.gameserver.common.DiamondLost","duihuanjinbi":0,"goumaidaoju":0,"
        //liuyanban":0,"shengjipaobei":0,"shijielaba":5000}'
        /**
         *  获取玩家,消费途径
         */
        $new_gold_lost = null;
        $gold_lost = $redis->hGetAll('diamondLost');
        if (array_key_exists($id, $gold_lost)) {
            $new_gold_lost = Json::decode($gold_lost[$id], true);
        }
        $datas = [];
        if ($new_gold_lost){
            foreach ($new_gold_lost as $key => $value) {
                if (array_key_exists('duihuanjinbi', $new_gold_lost)) {
                    $datas['兑换金币'] = $value;
                }
                if (array_key_exists('goumaidaoju', $new_gold_lost)) {
                    $datas['购买道具'] = $value;
                }
                if (array_key_exists('liuyanban', $new_gold_lost)) {
                    $datas['留言板'] = $value;
                }
                if (array_key_exists('shengjipaobei', $new_gold_lost)) {
                    $datas['深海捕鱼'] = $value;
                }
                if (array_key_exists('shijielaba', $new_gold_lost)) {
                    $datas['世界喇叭'] = $value;
                }
            }
        }
        
        return $this->render('diamond', ['data' => $data, 'datas' => $datas]);
    }
    
    
    /**
     *  查询玩家获取的金币
     */
    public function actionGetFishgold()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $redis = players::getReids();
       // $id = 254;
        
        /**
         *  获取金币的途径
         */
        $new_gold = null;
        $gold = $redis->hGetAll('fishGoldGet');
        if (array_key_exists($id, $gold)) {
            $new_gold = Json::decode($gold[$id], true);
        }
        //  254 => string '{"@class":"com.sinysoft.gameserver.common.FishGoldGet","buyu":0,
        //"buyusongbi":0,"chongzhizengsong":0,"cijiyouxi":0,"duihuanma":0,"gonggao":0,"jiujijin":0,
        //"meiriqiandao":0,"meirirenwu":0,"vipmeiri":0,"youjian":0}' (len
        $data = [];
        if ($new_gold){
            foreach ($new_gold as $key => $value) {
                if (array_key_exists('buyu', $new_gold)) {
                    $data['捕鱼'] = $value;
                }
                if (array_key_exists('buyusongbi', $new_gold)) {
                    $data['捕鱼送币'] = $value;
                }
                if (array_key_exists('chongzhizengsong', $new_gold)) {
                    $data['充值赠送'] = $value;
                }
                if (array_key_exists('cijiyouxi', $new_gold)) {
                    $data['刺激游戏'] = $value;
                }
                if (array_key_exists('duihuanma', $new_gold)) {
                    $data['兑换码'] = $value;
                }
                if (array_key_exists('gonggao', $new_gold)) {
                    $data['公告'] = $value;
                }
                if (array_key_exists('jiujijin', $new_gold)) {
                    $data['救济金'] = $value;
                }
                if (array_key_exists('meiriqiandao', $new_gold)) {
                    $data['每日签到'] = $value;
                }
                if (array_key_exists('meirirenwu', $new_gold)) {
                    $data['每日任务'] = $value;
                }
                if (array_key_exists('vipmeiri', $new_gold)) {
                    $data['vip任务'] = $value;
                }
                if (array_key_exists('youjian', $new_gold)) {
                    $data['邮件'] = $value;
                }
        
            }
        }
        
        
        /**
         *  获取玩家,消费途径
         */
        $new_gold_lost = null;
        $gold_lost = $redis->hGetAll('fishGoldLost');
        if (array_key_exists($id, $gold_lost)) {
            $new_gold_lost = Json::decode($gold_lost[$id], true);
        }
        //  254 => string '{"@class":"com.sinysoft.gameserver.common.FishGoldLost",
        //"buyuxiaohao":200,"cijiyouxi":0}' (length=88)
        $datas = [];
        if ($new_gold_lost){
            foreach ($new_gold_lost as $key => $value) {
                if (array_key_exists('buyuxiaohao', $new_gold_lost)) {
                    $datas['捕鱼消耗'] = $value;
                }
                if (array_key_exists('cijiyouxi', $new_gold_lost)) {
                    $datas['刺激游戏'] = $value;
                }
        
            }
        }
        return $this->render('fishgold', ['data' => $data, 'datas' => $datas]);
    }
    
}
