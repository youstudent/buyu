<?php

namespace backend\models;

use common\models\Experience;
use Yii;

/**
 * This is the model class for table "{{%entryconfig}}".
 *
 * @property integer $id
 * @property integer $cjuseable
 * @property integer $reduseable
 * @property integer $fishgolduseable
 * @property integer $tooloneuseable
 * @property integer $tooltwouseable
 * @property integer $toolthreeuseable
 * @property integer $toolfouruseable
 * @property integer $toolfiveuseable
 * @property integer $toolsixuseable
 * @property integer $familyuseable
 * @property integer $shopuseable
 * @property integer $vipuseable
 */
class Entryconfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entryconfig}}';
    }

    /**
     * @return
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
            [['cjuseable', 'reduseable', 'fishgolduseable', 'tooloneuseable', 'tooltwouseable', 'toolthreeuseable', 'toolfouruseable', 'toolfiveuseable', 'toolsixuseable', 'familyuseable', 'shopuseable', 'vipuseable'
            ,'vipuseable','nnuseable','erlfduseable','rpgcuseable','rpdcuseable','rpfcuseable'
            ], 'required'],
            [['cjuseable', 'reduseable', 'fishgolduseable', 'tooloneuseable', 'tooltwouseable', 'toolthreeuseable', 'toolfouruseable', 'toolfiveuseable', 'toolsixuseable', 'familyuseable', 'shopuseable', 'vipuseable'
            ,'vipuseable','nnuseable','erlfduseable','rpgcuseable','rpdcuseable','rpfcuseable'
            ], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cjuseable' => '刺激游戏',
            'fishgolduseable' => '鱼币场是否开启',
            'reduseable' => '红包是否开启',
            'tooloneuseable' => '道具1是否启用',
            'tooltwouseable' => '道具2是否启用',
            'toolthreeuseable' => '道具3是否启用',
            'toolfouruseable' => '道具4是否启用',
            'toolfiveuseable' => '道具5是否启用',
            'toolsixuseable' => '道具6是否启用',
            'familyuseable' => '家族是否启用',
            'shopuseable' => '商城是否启用',
            'vipuseable' => '龙虎斗',
            'nnUseable' => '牛牛',
            'erlfduseable' => '二人龙虎斗',
            'rpgcuseable' => '金币兑换',
            'rpdcuseable' => '钻石兑换',
            'rpfcuseable' => '宝石兑换',
        ];
    }
    
    /**
     * 修改状态
     * @param $data
     * @return bool
     */
    public function editStatus($data)
    {
        $row = Entryconfig::findOne(['id' => $data['id']]);
        if ($data['type'] == 1) {
            $row->cjuseable = $data['status'];
        }
        if ($data['type'] == 2) {
            $row->fishgolduseable = $data['status'];
        }
        if ($data['type'] == 3) {
            $row->tooloneuseable = $data['status'];
        }
        if ($data['type'] == 4) {
            $row->tooltwouseable = $data['status'];
        }
        if ($data['type'] == 5) {
            $row->toolthreeuseable = $data['status'];
        }
        if ($data['type'] == 6) {
            $row->toolfouruseable = $data['status'];
        }
        if ($data['type'] == 7) {
            $row->toolfiveuseable = $data['status'];
        }
        if ($data['type'] == 8) {
            $row->toolsixuseable = $data['status'];
        }
        if ($data['type'] == 9) {
            $row->familyuseable = $data['status'];
        }
        if ($data['type'] == 10) {
            $row->shopuseable = $data['status'];
        }
        if ($data['type'] == 11) {
            $row->vipuseable = $data['status'];
        }
        if ($data['type'] == 12) {
            $row->reduseable = $data['status'];
        }
        if ($data['type'] == 30) {
            $row->lfduseable = $data['status'];
        }
        if ($data['type'] == 31) {
            $row->nnuseable = $data['status'];
        }
        if ($data['type'] == 32) {
            $row->erlfduseable = $data['status'];
        }
        if ($data['type'] == 33) {
            $row->rpgcuseable = $data['status'];
        }
        if ($data['type'] == 34) {
            $row->rpdcuseable = $data['status'];
        }
        if ($data['type'] == 35) {
            $row->rpfcuseable = $data['status'];
        }
        return $row->save(false);
    }
    
    /**
     * @param $id
     * @return string `lfduseable` int(11) DEFAULT NULL COMMENT '龙虎斗开关',
    `nnuseable` int(11) DEFAULT NULL COMMENT '牛牛开关',
    `erlfduseable`二人龙虎斗
     * `rpgcuseable` int(11) DEFAULT NULL,
    `rpdcuseable` int(11) DEFAULT NULL,
    `rpfcuseable
     */
    
    public static function getShop($id){
       if ($data = Shop::findOne(['id'=>$id])){
           return $data->name;
        }
           return '没查询到道具名';
    }
}
