<?php

namespace backend\controllers;

use backend\models\DayForm;
use backend\models\ExpertForm;
use backend\models\LandForm;
use backend\models\OneCannonForm;
use Codeception\Util\JsonArray;
use common\models\DayList;
use common\models\DayTask;
use common\models\Test;
use common\services\Request;
use yii\helpers\Json;
use yii\web\Response;

class DayTaskController extends ObjectController
{
    /**
     *  捕鱼每日任务列表
     * @return string
     */
    public function actionIndex()
    {
        $data = DayTask::find()->asArray()->all();
        
        return $this->render('index',['data'=>$data]);
    }
    
    /**
     *  捕鱼每日任务列表
     * @return string
     */
    public function actionList()
    {
        $data = DayList::find()->asArray()->all();
        return $this->render('list',['data'=>$data]);
    }
    
    
    /**
     *  修改每日任务
     * @return array|string
     */
    public function actionEdit()
    {
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(5);
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $model->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = DayTask::setFishing();
        $i='z';
        foreach ($JSON as $key=>$value){
            if (array_key_exists($key.$i,$re)){
                $data[$key.$i]=$value;
            }
            /*if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$v['toolId']]=$v['toolNum'];
                    }
                }
            }*/
        }
        
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        
        $JSONS = json_decode($model->content,true);
        $datas  =[];
        $res = DayTask::$fishing;
            /**
             *   解析升级礼包
             */
          foreach ($JSONS as $key=>$value){
                if (array_key_exists($key,$res)){
                    $datas[$key]=$value;
                }
            }
             $types=[];
            foreach($datas as $k=>$v){
                $types[]=$k;
            }
        /*$a = trim($model->from_fishing, "[");
        $b = trim($a, "]");
        $c = explode(",", $b);
        $model->from_fishing=$c;*/
        //$model->fish_number=$types;
       // $model->package=$type;
        return $this->render('edit',['model'=>$model,'data'=>$data,'datas'=>$datas]);
    }
    
    
    /**
     *   登录,分享游戏, 道具商城
     */
    public function actionLand(){
       
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['type_id'=>$id]);
        $modelForm = new LandForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
        
        }
        
        $products = json_decode($model->content,true);
        $rows = Test::get($products['send']);
        $modelForm->id=$model->id;
        $modelForm->typeId= $model->type_id;
        $modelForm->gives=$rows['type'];
        $modelForm->enable=$model->status;
        $modelForm->description=$model->description;
        if ($model->type_id ==1){
            return $this->render('land',['model'=>$modelForm,'data'=>$rows['data']]);
        }elseif ($model->type_id ==8){
            return $this->render('share',['model'=>$modelForm,'data'=>$rows['data']]);
        }else{
            return $this->render('prop',['model'=>$modelForm,'data'=>$rows['data']]);
        }
        
    }
    
    
    /**
     *   技能使用,友谊互动
     */
    public function actionNum(){
        
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['type_id'=>$id]);
        $modelForm = new LandForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->editnum(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $data=[];
        $products = json_decode($model->content,true);
        $re = DayTask::$give;
        foreach ($products['send'] as $key=>$value){
            if (array_key_exists($key,$re)){
                $data[$key]=$value;
            }
            if(is_array($value)){
                foreach ($value as $K=>$v){
                    if (array_key_exists($v['toolId'],$re)){
                        $data[$v['toolId']]=$v['toolNum'];
                    }
                }
            }
            
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $modelForm->id=$model->id;
        $modelForm->typeId= $model->type_id;
        $modelForm->gives=$type;
        $modelForm->num=$products['num'];
        $modelForm->enable=$model->status;
        if ($model->type_id ==11){
            return $this->render('skill',['model'=>$modelForm,'data'=>$data]);
        }else{
            return $this->render('assn',['model'=>$modelForm,'data'=>$data]);
        }
        
    }
    
    
    
    
    
    //同步数据
    public function actionGetDay(){
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $code = DayTask::GetDay();
        if ($code ==1){
            return ['code'=>1,'message'=>'同步成功'];
        }
        return ['code'=>0,'message'=>'同步失败'];
    }
    
    /**
     *  捕鱼能手列表
     */
    public function actionExpert(){
        $data = DayTask::find()->where(['type_id'=>2])->asArray()->all();
        return $this->render('expert',['data'=>$data]);
    }
    
    /**
     *  添加捕鱼能手任务
     */
    public function actionAddExpert(){
        $this->layout = false;
        $modelForm = new ExpertForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        
        }
        $modelForm->typeId=2;
        return $this->render('add-expert', ['model' =>$modelForm]);
        
    }
    
    /**
     *   修改
     */
    public function actionUpdate()
    {
    
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id' => $id]);
        $modelForm = new ExpertForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        
        }
        $data = [];
        $products = json_decode($model->content, true);
        $re = DayTask::$give;
        foreach ($products['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        
        }
        $type = [];
        foreach ($data as $k => $v) {
            $type[] = $k;
        }
        
        $modelForm->id = $model->id;
        $modelForm->enable = $model->status;
        $modelForm->typeId = $model->type_id;
        $modelForm->num = $products['num'];
        $modelForm->gives = $type;
        $modelForm->fishings = $products['fishId'];
        return $this->render('update-expert', ['model' => $modelForm, 'data' => $data]);
    
    }
    
   
    /**
     *   修改今日斗金
     */
    public function actionUpdateToday()
    {
        
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id' => $id]);
        $modelForm = new DayForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->edit(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $data = [];
        $products = json_decode($model->content, true);
        $re = DayTask::$give;
        foreach ($products['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
            
        }
        $type = [];
        foreach ($data as $k => $v) {
            $type[] = $k;
        }
        $modelForm->id = $model->id;
        $modelForm->enable = $model->status;
        $modelForm->typeId = $model->type_id;
        $modelForm->num = $products['num'];
        $modelForm->gold = $products['type'];
        $modelForm->gives = $type;
        return $this->render('update-today', ['model' => $modelForm, 'data' => $data]);
        
    }
    
    
    
    
    /**
     * 今日斗金列表
     */
    public function actionMoneyToday(){
       $data = DayTask::find()->where(['type_id'=>3])->asArray()->all();
       return $this->render('money-today',['data'=>$data]);
    }
    
    /**
     *  添加每日消耗的 货币数量,配置,礼包   每日斗金
     * @return array|string
     */
    public function actionAddDay(){
        $this->layout = false;
        $modelForm = new DayForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId=3;
        return $this->render('add-day', ['model' =>$modelForm]);
        
    }
    
    
    /**
     *  鱼王列表
     */
    public function actionKing(){
        $data = DayTask::find()->where(['type_id'=>4])->asArray()->all();
        return $this->render('fish-king',['data'=>$data]);
    }
    
    
    /**
     *  添加鱼王任务
     */
    public function actionAddKing(){
        $this->layout = false;
        $modelForm = new ExpertForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->add(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId=4;
        return $this->render('add-king', ['model' =>$modelForm]);
        
    }
    
    /**
     * 惊天一炮修改
     */
      public function actionOneCannon(){
          $this->layout = false;
          $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
          $model = DayTask::findOne(['id'=>$id]);
          $modelForm = new OneCannonForm();
          if(\Yii::$app->request->isPost)
          {
              \Yii::$app->response->format = Response::FORMAT_JSON;
              if($modelForm->edit(\Yii::$app->request->post()))
              {
                  return ['code'=>1,'message'=>'修改成功'];
              }
              $message = $modelForm->getFirstErrors();
              $message = reset($message);
              return ['code'=>0,'message'=>$message];
        
          }
          $JSON = json_decode($model->content,true);
          $data  =[];
          $re = OneCannonForm::$give;
          foreach ($JSON['send'] as $key => $value) {
              if (array_key_exists($key, $re)) {
                  $data[$key] = $value;
              }
              if (is_array($value)) {
                  foreach ($value as $K => $v) {
                      if (array_key_exists($v['toolId'], $re)) {
                          $data[$v['toolId']] = $v['toolNum'];
                      }
                  }
              }
          }
          $type=[];
          foreach($data as $k=>$v){
              $type[]=$k;
          }
          $modelForm->id=$model->id;
          $modelForm->typeId=$model->type_id;
          $modelForm->gives=$type;
          $modelForm->num=$JSON['num'];
          $modelForm->enable=$model->status;
          $modelForm->type1=$JSON['type'];
          return $this->render('one',['model'=>$modelForm,'data'=>$data]);
      }
    
    
    /**
     *  添加惊天炮
     */
    public function actionAddOne()
    {
        $this->layout = false;
        $modelForm = new OneCannonForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->addcannon(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId = 6;
        return $this->render('add-one', ['model' => $modelForm]);
        
    }
    
    
    /**
     *  删除任务
     * @return array
     */
    public function actionDel()
    {
        $this->layout = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id     = \Yii::$app->request->get('id');
        $model  = DayTask::findOne($id);
        $data =[];
        $data['id']=$model->id;
        $datas = Json::encode($data);
        $url = \Yii::$app->params['Api'].'/control/deleteEveryDayTask';
        $re = Request::request_post_raw($url,$datas);
        if ($re['code']==1){
            $model->delete();
            return ['code'=>1,'message'=>'删除成功'];
        }
        return ['code'=>0,'message'=>'删除失败'];
    }
    
    
    
    /**
     *  虚 无弹发 修改
     */
    public function actionCannon(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id'=>$id]);
        $modelForm = new OneCannonForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = OneCannonForm::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $modelForm->id=$model->id;
        $modelForm->typeId=$model->type_id;
        $modelForm->gives=$type;
        $modelForm->get=$JSON['get'];
        $modelForm->lost=$JSON['lost'];
        $modelForm->type1=$JSON['type'];
        $modelForm->enable=$model->status;
        return $this->render('cannon',['model'=>$modelForm,'data'=>$data]);
    }
    
    
    /**
     *  弹无须发
     */
    public function actionCannonIndex(){
        $data = DayTask::find()->where(['type_id'=>5])->asArray()->all();
        return $this->render('cannon-index',['data'=>$data]);
    }
    
    
    /**
     *  惊天一炮
     */
    public function actionOneIndex(){
        $data = DayTask::find()->where(['type_id'=>6])->asArray()->all();
        return $this->render('one-index',['data'=>$data]);
    }
    
    
    
    /**
     *  挥金如土
     */
    public function actionWaste(){
        $data = DayTask::find()->where(['type_id'=>7])->asArray()->all();
        return $this->render('waste-index',['data'=>$data]);
    }
    
    /**
     *  挥金如土添加
     */
    public function actionAddWaste()
    {
        $this->layout = false;
        $modelForm = new OneCannonForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->addcannon(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId = 7;
        return $this->render('add-waste', ['model' => $modelForm]);
        
    }
    
    
    
    /**
     *   修改土
     */
    public function actionUpdateWaste(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id'=>$id]);
        $modelForm = new OneCannonForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = OneCannonForm::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $modelForm->id=$model->id;
        $modelForm->typeId=$model->type_id;
        $modelForm->gives=$type;
        $modelForm->enable=$model->status;
        $modelForm->number=$JSON['num'];
        $modelForm->type1=$JSON['type'];
        return $this->render('update-waste',['model'=>$modelForm,'data'=>$data]);
    }
    
    
    
    /**
     *  添加虚无弹发
     */
    public function actionAddCannon()
    {
        $this->layout = false;
        $modelForm = new OneCannonForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->addcannon(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
        
        }
        $modelForm->typeId = 5;
        return $this->render('add-cannons', ['model' => $modelForm]);
        
    }
    
    
    /**
     *   决战深海
     */
    public function actionDeepSea(){
        $data = DayTask::find()->where(['type_id'=>9])->asArray()->all();
        return $this->render('deep-sea',['data'=>$data]);
        
    }
    
    /**
     * 添加决战深海
     * @return array|string
     */
    public function actionAddDeepSea()
    {
        $this->layout = false;
        $modelForm = new OneCannonForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->addcannon(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId = 9;
        return $this->render('add-deep-sea', ['model' => $modelForm]);
    }
    
    
    /**
     *   决战深海修改
     */
    public function actionUpdateDeep(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id'=>$id]);
        $modelForm = new OneCannonForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = OneCannonForm::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $modelForm->id=$model->id;
        $modelForm->typeId=$model->type_id;
        $modelForm->gives=$type;
        $modelForm->lost=$JSON['num'];
        $modelForm->type1=$JSON['type'];
        $modelForm->enable=$model->status;
        return $this->render('update-deep',['model'=>$modelForm,'data'=>$data]);
    }
    
    
    /**
     *  游戏在线时长
     */
    public function actionGameIndex(){
        $data = DayTask::find()->where(['type_id'=>10])->asArray()->all();
        return $this->render('game-index',['data'=>$data]);
    }
    
    
    /**
     * 添加 游戏在线时长
     * @return array|string
     */
    public function actionAddGame()
    {
        $this->layout = false;
        $modelForm = new OneCannonForm();
        if (\Yii::$app->request->isPost) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if ($modelForm->addcannon(\Yii::$app->request->post())) {
                return ['code' => 1, 'message' => '添加成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code' => 0, 'message' => $message];
            
        }
        $modelForm->typeId = 10;
        return $this->render('add-game', ['model'=>$modelForm]);
    }
    
    
    /**
     *   持之以恒
     */
    public function actionUpdateGame(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne(['id'=>$id]);
        $modelForm = new OneCannonForm();
        if(\Yii::$app->request->isPost)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if($modelForm->edit(\Yii::$app->request->post()))
            {
                return ['code'=>1,'message'=>'修改成功'];
            }
            $message = $modelForm->getFirstErrors();
            $message = reset($message);
            return ['code'=>0,'message'=>$message];
            
        }
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = OneCannonForm::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        }
        $type=[];
        foreach($data as $k=>$v){
            $type[]=$k;
        }
        $modelForm->id=$model->id;
        $modelForm->typeId=$model->type_id;
        $modelForm->gives=$type;
        $modelForm->time=($JSON['num']/1000/60);
        $modelForm->enable=$model->status;
        return $this->render('update-game',['model'=>$modelForm,'data'=>$data]);
    }
    
    
    /**
     *   基础任务查看详情
     */
    public function actionPrize(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayList::findOne($id);
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = DayList::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                $data[$key] = $value;
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        $data[$v['toolId']] = $v['toolNum'];
                    }
                }
            }
        }
        return $this->render('prize',['model'=>$model,'data'=>$data]);
        
    }
    
    
    /**
     * 查看奖品
     * @return string
     */
    public function actionPrizeExpert(){
        $this->layout = false;
        $id = empty(\Yii::$app->request->get('id')) ? \Yii::$app->request->post('id') : \Yii::$app->request->get('id');
        $model = DayTask::findOne($id);
        $JSON = json_decode($model->content,true);
        $data  =[];
        $re = DayTask::$give;
        foreach ($JSON['send'] as $key => $value) {
            if (array_key_exists($key, $re)) {
                if ($value>0){
                    $data[$key] = $value;
                }
                
            }
            if (is_array($value)) {
                foreach ($value as $K => $v) {
                    if (array_key_exists($v['toolId'], $re)) {
                        if ($v['toolNum']>0){
                            $data[$v['toolId']] = $v['toolNum'];
                        }
                        
                    }
                }
            }
        }
        return $this->render('prize-expert',['model'=>$model,'data'=>$data]);
    }
}
