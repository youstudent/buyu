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
            [['cjuseable', 'reduseable', 'fishgolduseable', 'tooloneuseable', 'tooltwouseable', 'toolthreeuseable', 'toolfouruseable', 'toolfiveuseable', 'toolsixuseable', 'familyuseable', 'shopuseable', 'vipuseable'], 'required'],
            [['cjuseable', 'reduseable', 'fishgolduseable', 'tooloneuseable', 'tooltwouseable', 'toolthreeuseable', 'toolfouruseable', 'toolfiveuseable', 'toolsixuseable', 'familyuseable', 'shopuseable', 'vipuseable'], 'integer'],
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
            'vipuseable' => 'vip是否启用',
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
        return $row->save(false);
    }
    
    
    public static function getShop($id){
       if ($data = Shop::findOne(['id'=>$id])){
           return $data->name;
        }
           return '位置道具名';
    }
}
