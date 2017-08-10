<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "familyplayer".
 *
 * @property integer $id
 * @property integer $familyid
 * @property integer $playerid
 * @property integer $status
 * @property string $gold
 * @property string $diamond
 * @property string $fishgold
 * @property string $applytime
 * @property string $agreetime
 */
class Familyplayer extends \yii\db\ActiveRecord
{
    
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $starttime     = '';
    
    /**
     * 时间筛选开始时间
     * @return array
     */
    public $endtime      = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'familyplayer';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        //return Yii::$app->get('commondb');
        return Yii::$app->commondb;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['familyid', 'playerid'], 'required'],
            [['familyid', 'playerid', 'status', 'gold', 'diamond', 'fishgold', 'applytime', 'agreetime','id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'familyid' => '家族ID',
            'playerid' => '玩家ID',
            'status' => '状态',
            'gold' => '玩家保险箱金币',
            'diamond' => '钻石',
            'fishgold' => '宝石',
            'applytime' => '申请时间',
            'agreetime' => '通过时间',
        ];
    }
    
    
    public function getList($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['>=','applytime',strtotime($this->starttime)])->andWhere(['<=','applytime',strtotime($this->endtime)])->andWhere(['familyid'=>\Yii::$app->session->get('familyId'),'status'=>1]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    public function son($data = [])
    {
        $this->load($data);
        $this->initTime();
        $model   = self::find()->andWhere(['>=','applytime',strtotime($this->starttime)])->andWhere(['<=','applytime',strtotime($this->endtime)])->andWhere(['familyid'=>$this->id,'status'=>1]);
        $pages = new Pagination(
            [
                'totalCount' =>$model->count(),
                'pageSize' => \Yii::$app->params['pageSize']
            ]
        );
        
        $data  = $model->limit($pages->limit)->offset($pages->offset)->all();
        return ['data'=>$data,'pages'=>$pages,'model'=>$this];
    }
    
    
    /**
     * 检查筛选条件时间时间
     * 方法不是判断是否有错 是初始化时间
     */
    public function initTime()
    {
        if($this->starttime == '') {
            $this->starttime = \Yii::$app->params['startTime'];
        }
        if($this->endtime == '') {
            $this->endtime = date('Y-m-d H:i:s');
        }
    }
    
    
    /**
     *   族长玩家关系表, 建立玩家关系表
     */
    public function getSon(){
        return $this->hasOne(Player::className(),['id'=>'playerid']);
    }
}
