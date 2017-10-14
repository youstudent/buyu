<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%everydaytask}}".
 *
 * @property integer $id
 * @property string $content
 * @property string $taskname
 * @property integer $typeId
 * @property integer $enable
 * @property string $description
 */
class Everydaytask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%everydaytask}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commondb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'taskname', 'typeId', 'enable'], 'required'],
            [['content'], 'string'],
            [['typeId', 'enable'], 'integer'],
            [['taskname'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '礼包',
            'taskname' => '任务名',
            'typeId' => '类型',
            'enable' => '状态',
            'description' => '描述',
        ];
    }
    
    /**
     *  基础任务详情
     */
    public static function getBasics($data){
        $JSON = json_decode($data,true);
        if (array_key_exists('num',$JSON)){
            return $JSON['num'].'次';
        }
        return '不存在次数';
    }
    
    
    /**
     * 修改 登录  分享   道具
     * @param array $data
     * @return bool|void
     */
    public function edit($data = []){
        if($this->load($data) && $this->validate())
        {
            $arr = [];
            $content = \common\models\Test::set($this->type);
            if (!$content){
                return $this->addError('types','数量无效');
            }
            $arr['enable']=$this->enable;
            $arr['id']=$this->id;
            $arr['typeId']=$this->typeId;
            $arr['content']=$content;
            $arr['description']=$this->description;
            $JS = Json::encode($arr);
            $url = \Yii::$app->params['Api'].'/control/updateEveryDayTask';
            $re = Request::request_post_raw($url,$JS);
            if ($re['code']== 1){
                $row= DayList::findOne(['type_id'=>$this->typeId]);
                $rows = DayTask::findOne(['type_id'=>$this->typeId]);
                $rows->status =$this->enable;
                $rows->description =$this->description;
                $rows->content =Json::encode($content);
                $rows->save(false);
                $row->status=$this->enable;
                $row->description=$this->description;
                $row->content=Json::encode($content);
                $row->save(false);
                return true;
            }
            
        }
    }
    
    
    /**
     *  提取鱼名字 和击杀数量
     */
    public static function fishing($data){
        $JSON = json_decode($data,true);
        $fishing_id =$JSON['fishId'];
        $num =$JSON['num'];
        $row = Fish::findOne(['id'=>$fishing_id]);
        if ($row){
            $name =$row->name;
            return '击杀'.$name.$num.'条';
        }
        return '';
    }
    
    
    public static function getFishingType($data){
        $JSON = json_decode($data,true);
        $fishing_id =$JSON['fishId'];
        $re =  Fish::findOne(['id'=>$fishing_id]);
        if ($re){
            if ($re->fishtype ==1){
                return '小鱼';
            }
            if ($re->fishtype ==2){
                return '中鱼';
            }
            if ($re->fishtype ==3){
                return '大鱼';
            }
            if ($re->fishtype ==4){
                return '金鱼';
            }
            if ($re->fishtype ==5){
                return 'BOOS';
            }
        }
        return '';
    }
}
